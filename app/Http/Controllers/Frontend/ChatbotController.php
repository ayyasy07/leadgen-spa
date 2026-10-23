<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\Treatment;
use App\Models\Package;
use App\Models\Setting;

class ChatbotController extends Controller
{
    private function getSessionKey()
    {
        return Auth::check() ? 'chatbot_history_user_' . Auth::id() : 'chatbot_history_guest';
    }

    public function getHistory()
    {
        $sessionKey = $this->getSessionKey();
        $history = session()->get($sessionKey, []);
        
        $now = now();
        $validHistory = array_filter($history, function($h) use ($now) {
            return isset($h['created_at']) && $now->diffInHours($h['created_at']) < 24;
        });

        $formatted = array_map(function($h) {
            return [
                'sender' => $h['sender'],
                'text' => $h['text'], 
                'status' => 'done'
            ];
        }, array_values($validHistory));

        return response()->json(['history' => $formatted]);
    }

    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'history' => 'nullable|array'
        ]);

        $rawMessage = trim($request->message);
        $message = strtolower($rawMessage);
        $history = $request->input('history', []);
        $sessionKey = $this->getSessionKey();

        $replyJson = function($reply, $suggestions = []) use ($rawMessage, $sessionKey) {
            $history = session()->get($sessionKey, []);
            $history[] = ['sender' => 'user', 'text' => $rawMessage, 'created_at' => now()];
            $history[] = ['sender' => 'bot', 'text' => $reply, 'created_at' => now()];
            $history = array_slice($history, -20);
            session()->put($sessionKey, $history);
            return response()->json(['reply' => $reply, 'suggestions' => $suggestions]);
        };

        // 1. CEK STATUS BOOKING
        if ((str_contains($message, 'check my booking') || str_contains($message, 'booking status') || str_contains($message, 'status booking') || str_contains($message, 'cek booking') || str_contains($message, 'status pesanan'))) {
            if (!Auth::check()) {
                return $replyJson("To check your booking status, please <a href='/login' target='_blank' class='chat-link'>Log In</a> to your account first.", ['How to Book', 'FAQ']);
            } else {
                return $replyJson("Please provide your Booking Code (e.g., <strong>BKG-XXXX</strong>) so I can check it for you.", ['How to Cancel?', 'FAQ']);
            }
        }

        // 2. CEK KODE BOOKING
        if (preg_match('/\b(BKG-[0-9]{6}-[A-Z0-9]{4})\b/i', $rawMessage, $matches)) {
            $code = strtoupper($matches[1]);
            if (!Auth::check()) {
                return $replyJson("To check your booking schedule, you must be logged in. Please <a href='/login' target='_blank' class='chat-link'>Log In</a> first.", ['How to Book', 'FAQ']);
            }
            $booking = Booking::where('booking_code', $code)->where('user_id', Auth::id())->first();
            if ($booking) {
                $date = $booking->booking_date ? $booking->booking_date->format('l, d F Y') : '-';
                $reply = "<strong>I found your booking!</strong><br>🎫 Code: {$booking->booking_code}<br>📅 Date: {$date}<br>⏰ Time: {$booking->booking_time}<br>📌 Status: {$booking->status_label}<br>";
                if ($booking->status === 'pending' && $booking->canBePaid()) {
                    $payUrl = route('payment.create', $booking->booking_code);
                    $csrf = csrf_token();
                    $reply .= "<form action='{$payUrl}' method='POST' style='margin-top: 10px;'><input type='hidden' name='_token' value='{$csrf}'><input type='hidden' name='payment_method' value='{$booking->payment_method}'><input type='hidden' name='payment_method_name' value='{$booking->payment_method_name}'><input type='hidden' name='payment_fee' value='{$booking->payment_fee}'><button type='submit' style='background: var(--primary, #f76c6c); color: #fff; border: none; padding: 10px 20px; border-radius: 8px; font-weight: bold; cursor: pointer; width: 100%;'>💳 Pay Now</button></form>";
                } elseif ($booking->status === 'paid') {
                    $reply .= "Your payment is confirmed! We look forward to seeing you.";
                }
                return $replyJson($reply, ['How to Cancel?', 'Location', 'FAQ']);
            } else {
                return $replyJson("Sorry, I couldn't find a booking with code <strong>{$code}</strong> under your account.", ['Check my booking status', 'FAQ']);
            }
        }

        // 3. AI FALLBACK
        try {
            $isLoggedIn = Auth::check() ? 'Yes' : 'No';
            $categories = Treatment::where('is_active', 1)->whereNotNull('category')->distinct()->pluck('category')->toArray();
            $categoryList = implode(', ', $categories);

            $treatments = Treatment::where('is_active', 1)->get();
            $treatmentData = "";
            foreach ($treatments as $t) {
                $price = $t->prices->first() ? $t->prices->first()->price : 0;
                $desc = strip_tags($t->description) ?? 'No description';
                $treatmentData .= "- **{$t->name}** (Category: {$t->category} | Duration: {$t->duration} mins): Rp " . number_format($price, 0, ',', '.') . ". Desc: {$desc}\n";
            }

            $packages = Package::where('is_active', 1)->get();
            $packageData = "";
            foreach ($packages as $p) {
                $price = $p->price ?? 0;
                $desc = strip_tags($p->description) ?? 'No description';
                $packageData .= "- **{$p->name}**: Rp " . number_format($price, 0, ',', '.') . ". Desc: {$desc}\n";
            }

            // DATA DARI DB (NO HARDCODE)
            $address = Setting::get('contact_address', 'Check our Contact page.');
            $email = Setting::get('contact_email', 'hello@leadgenspa.com');
            $weekdayLabel = Setting::get('weekday_label', 'Mon - Fri');
            $weekdayOpen  = Setting::get('weekday_open', '10:00');
            $weekdayClose = Setting::get('weekday_close', '21:00');
            $weekendLabel = Setting::get('weekend_label', 'Saturday');
            $weekendOpen  = Setting::get('weekend_open', '10:00');
            $weekendClose = Setting::get('weekend_close', '22:00');
            $whatsapp = Setting::get('social_whatsapp', 'Check our footer'); 
            $instagram = Setting::get('social_instagram', 'Check our footer');

            $systemPrompt = "IMPORTANT: Your name is MiSpa, the AI virtual assistant for LeadGen Spa. NEVER prefix your response with 'Bot:', 'Assistant:', or your name. Just answer directly.
            
            You are a top-tier, emotionally intelligent Sales Consultant for LeadGen Spa. Your goal is to guide the user naturally, understand their feelings, and provide the best solution without being pushy.
            
            🚨 CRITICAL THINKING RULES:
            1. IDENTITY: If asked, you are MiSpa, an AI virtual assistant. You are NOT a human. 
            2. POLYGLOT: Reply in the EXACT SAME LANGUAGE as the user's LATEST message.
            3. INTENT OVER KEYWORDS: Understand what the user ACTUALLY means. If they say 'badan saya kaku' or 'I need to relax', that is an INTENT for 'Body/Massage treatments'.
            4. NO HALLUCINATION: You MUST ONLY answer using the data provided below. If the user asks for something not in the data, say 'I'm sorry, we don't have that information currently.'
            5. SOCIAL AWARENESS: If the user says a closing statement or gratitude, DO NOT upsell. Just acknowledge warmly and end gracefully.
            6. EMPATHY: Acknowledge user complaints before recommending a treatment.
            7. CONTEXT-AWARE: Understand the flow. Do NOT repeat your previous answer.
            8. CONCISENESS: Keep your answers SHORT, punchy, and direct. Do not over-explain unless the user asks for a deep dive. Aim for 1-3 short paragraphs max.
            
            🛡️ SECURITY & PRIVACY RULES (STRICT):
            - Current User Status: **{$isLoggedIn}** (Logged In / Guest).
            - FORBIDDEN FOR GUESTS: If the status is 'No', you are STRICTLY FORBIDDEN from explaining the Booking Code format (BKG-XXXX), how to find it, or how to change passwords/profile.
            - GUEST RESPONSE: If a Guest asks about those forbidden topics, reply: 'To access your booking codes or update your profile, you need to Log In first.'
            - ALLOWED FOR EVERYONE: You are ALWAYS ALLOWED to explain spa info, general cancellation steps, and contact admin to BOTH Guests and Logged-in users.
            - IF LOGGED IN (Yes): You are ALLOWED to explain the Booking Code format and how to change profile details.

            📋 BUSINESS & DATA RULES:
            9. DYNAMIC CATEGORIES: Our categories are: **{$categoryList}**.
            10. NO DUMPING: NEVER list all treatments at once. 
            11. CONSULTATION: If they ask for a recommendation, ask what they need. Once they answer, recommend ONLY 1 or 2 best treatments. Explain WHY.
            12. BOOKING STEPS: If they ask how to book, give them the step-by-step below. Use HTML <ol> for the steps.
            13. FAQ FORMATTING (STRICT): If the user asks for 'FAQ', you MUST ONLY output a short intro. THEN, you MUST put the actual questions into the SUGGESTIONS array at the very end of your response!
            14. PACKAGES RULE (STRICT): If they ask for 'cheap' or 'bundle', explain that we provide special **Packages** which are pre-set combinations at a lower price. DO NOT suggest custom bundles. Give a link to <a href='/packages' class='chat-link'>View Packages</a>.
            15. ACTIONABLE LINKS: Always include a link for the category you recommend. Example: <a href='/treatments?kategori=body' class='chat-link'>View Body Treatments</a>.
            
            16. FORMATTING (CRITICAL & STRICT): 
            - You MUST use HTML <ul> and <li> for ALL lists. 
            - Use nested <ul> ONLY if necessary (e.g., sub-points under Address). 
            - DO NOT use ANY emojis inside the <li> tags or in the list items. 
            - NEVER write a long paragraph without breaks. 
            - Avoid excessive nesting. Keep it flat and compact.
            
            17. REFUND POLICY FORMATTING: When explaining refund policies, you MUST use bullet points (<ul><li>) and bold (<strong>).
            18. TECH SUPPORT HANDOVER: If the user reports technical issues (e.g., 'I forgot my password', 'I can't log in'), DO NOT give a long tutorial. Reply politely: 'For security and technical issues like password resets, please contact our Admin directly via WhatsApp at {$whatsapp} or email at {$email} so they can assist you further. 🙏' (Translate to user's language).
            
            19. SMART SUGGESTIONS: At the very end, output: [SUGGESTIONS: \"Q1?\", \"Q2?\", \"Q3?\"]
            Analyze the IMMEDIATE PREVIOUS context and generate 3 highly specific follow-up questions.
            - If discussing a specific treatment: Ask about duration, ingredients, or booking that item.
            - If discussing packages: Ask about what's included or price differences.
            - If discussing history/cancel: Ask about refund status or rescheduling.
            NEVER use generic templates like 'How to Book' unless the user literally just said 'Hello'. 

            📍 WEB NAVIGATION & FLOW KNOWLEDGE:
            1. HOME PAGE: Shows hero, about, treatments, gallery, packages, testimonials, and contact.
            2. MENU: HOME, ABOUT, TREATMENTS, GALLERY, BLOG, CONTACT. The \"BOOK NOW\" button changes to a Cart icon on product pages.
            3. GUEST LIMITATION: Guests can ONLY browse pages, subscribe to newsletter, and send messages via Contact. Guests CANNOT add to cart or book.
            4. LOGIN REQUIREMENT: To add to cart or book, user MUST log in.
            5. CART & BOOKING: If a logged-in user adds to cart, the cart is saved. On Booking page, they see time slots, dates, notes. After confirming, they choose payment method and are redirected to Payment Gateway.
            6. PAYMENT & PENDING: Booking status stays 'Pending' for a limited time if unpaid.
            7. BOOKING CODE & HISTORY: Found on 'History' page. Access via hamburger menu (garis tiga) -> History. 
            8. CANCELLATION: To cancel, go to History -> click booking -> 'Cancel Booking' -> select reason -> proceed.
            9. PROFILE: To change name/email/password, click hamburger menu -> Profile. 
            10. HAMBURGER MENU: If logged in: History, Profile, Logout. If guest: Login, Register.
            
            IF YOU DON'T KNOW THE EXACT ANSWER: Never say 'I don't have that information'. Always guide them using the flow above.

            **OUR CATEGORIES:**
            {$categoryList}

            **OUR TREATMENTS:**
            {$treatmentData}
            
            **OUR SPECIAL PACKAGES:**
            {$packageData}

            **OUR LOCATION & CONTACT:**
            Address: {$address}
            Email: {$email}
            WhatsApp: {$whatsapp}
            Instagram: {$instagram}
            Operating Hours: 
            - {$weekdayLabel}: {$weekdayOpen} - {$weekdayClose}
            - {$weekendLabel}: {$weekendOpen} - {$weekendClose}
            
            **REFUND POLICY:**
            - Cancellations 24+ hours prior to appointment: eligible for a full refund or free reschedule.
            - Cancellations within 24 hours or no-shows: non-refundable.
            - Approved refunds: processed to the original payment method within 3-5 business days.
            - To request a refund or reschedule, contact support with your Booking ID.

            **BOOKING STEPS:**
            Step 1: Go to Treatments or Packages page.
            Step 2: Select the treatment and click 'Add to Cart'.
            Step 3: Click 'Book Now' at the top right header.
            Step 4: Fill details, choose schedule, and complete payment.
            *NOTE: Customers CANNOT customize their own bundle. 1 transaction = 1 schedule.
            ";

            $apiMessages = [['role' => 'system', 'content' => $systemPrompt]];

            $recentHistory = array_slice($history, -8); 
            foreach ($recentHistory as $h) {
                $cleanText = strip_tags($h['text']); 
                $apiMessages[] = [
                    'role' => $h['sender'] === 'user' ? 'user' : 'assistant',
                    'content' => $cleanText
                ];
            }

            $apiMessages[] = ['role' => 'user', 'content' => $rawMessage];

            $modelName = env('CHATBOT_MODEL', 'gemini-combos');
            $modelName = str_replace('agy/', '', $modelName);

            $response = Http::withToken(env('CHATBOT_API_KEY'))
                ->timeout(30) 
                ->post(env('CHATBOT_API_URL'), [
                    'model' => $modelName, 
                    'messages' => $apiMessages
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $reply = $data['choices'][0]['message']['content'] ?? 'Sorry, I did not understand that.';
                
                $suggestions = [];
                if (preg_match('/\[SUGGESTIONS:\s*(.*?)\]/is', $reply, $matches)) {
                    $sugStr = $matches[1];
                    $reply = str_replace($matches[0], '', $reply);
                    if (preg_match_all('/"(.*?)"/', $sugStr, $sugMatches)) {
                        $suggestions = $sugMatches[1];
                    }
                }

                if (empty($suggestions) || count($suggestions) < 3) {
                    if (str_contains($message, 'book') || str_contains($message, 'reserv') || str_contains($message, 'pesan')) {
                        $suggestions = ['See Packages', 'Opening Hours', 'Payment Methods'];
                    } elseif (str_contains($message, 'treatment') || str_contains($message, 'facial') || str_contains($message, 'massage')) {
                        $suggestions = ['See Packages', 'How to Book', 'Contact Admin'];
                    } elseif (str_contains($message, 'cancel') || str_contains($message, 'refund')) {
                        $suggestions = ['How to Book', 'Contact Admin', 'Location'];
                    } elseif (str_contains($message, 'faq')) {
                        $suggestions = ['What are your hours?', 'Where are you located?', 'How do I book?'];
                    } else {
                        $suggestions = ['See Treatments', 'See Packages', 'Contact Admin'];
                    }
                }

                // ==========================================
                // FIX JARAK & MARKDOWN (SMART REGEX)
                // ==========================================
                // 1. Bold Markdown
                $reply = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', trim($reply));
                $reply = preg_replace('/\*(.*?)\*/', '<strong>$1</strong>', $reply);
                
                // 2. HAPUS EMOJI DI AWAL LIST (SUPAYA GAK DOBEL & RAPI)
                $reply = preg_replace('/^(\s*(?:-|\*|•))\s*([\x{1F300}-\x{1FAFF}\x{2600}-\x{27BF}\x{1F1E6}-\x{1F1FF}]+)\s*(.*)$/mu', '$1 $3', $reply);
                
                // 3. Ubah format list jadi <li>
                $reply = preg_replace('/^\s*(?:\d+\.|-|\*|•)\s+(.*)$/m', '<li>$1</li>', $reply);
                
                // 4. Bungkus <li> jadi <ul> & Rapetin
                $reply = preg_replace('/(<li>.*<\/li>)/s', '<ul class="chat-list">$1</ul>', $reply);
                $reply = preg_replace('/<\/ul>\s*<ul class="chat-list">/i', '', $reply); 
                $reply = preg_replace('/<li>\s*<br>/i', '<li>', $reply); 
                $reply = preg_replace('/<br>\s*<\/li>/i', '</li>', $reply);
                
                // 5. Bersihin Enter Berlebih (LDR)
                $reply = preg_replace('/\n+/', ' ', $reply); 
                
                // 6. Bersihin prefix "MiSpa:"
                $reply = preg_replace('/^(MiSpa|Bot|Assistant):\s*/i', '', $reply);

                // 7. Rapetin spasi dobel
                $reply = preg_replace('/\s+/', ' ', $reply);
                $reply = str_replace(' <li>', '<li>', $reply);
                $reply = str_replace('</li> ', '</li>', $reply);
                $reply = str_replace(' <ul', '<ul', $reply);
                $reply = str_replace('</ul> ', '</ul>', $reply);

                return $replyJson($reply, $suggestions);
            }
            
            return $replyJson("I'm having trouble connecting right now. Please check our <a href='/treatments' class='chat-link'>Treatments page</a>.", ['FAQ', 'How to Book']);

        } catch (\Exception $e) {
            return $replyJson("I'm taking too long to answer. You can check our <a href='/treatments' class='chat-link'>Treatments</a> directly.", ['How to Book', 'Contact']);
        }
    }
}