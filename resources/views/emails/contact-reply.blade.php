<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reply</title>
</head>
<body style="font-family:Arial,sans-serif;background:#f4f1ef;padding:30px;margin:0">
    <div style="max-width:600px;margin:0 auto;background:#fff;border-radius:12px;overflow:hidden">

        <div style="background:#3b82f6;padding:24px 30px">
            <h1 style="color:#fff;margin:0;font-size:18px">LeadGen Spa</h1>
        </div>

        <div style="padding:30px">
            <p style="color:#334155;font-size:14px;margin:0 0 16px">
                Halo <strong>{{ $contactMessage->name }}</strong>,
            </p>

            <div style="color:#334155;font-size:14px;line-height:1.7;white-space:pre-line">
                {!! nl2br(e($replyText)) !!}
            </div>

            <hr style="border:none;border-top:1px solid #e7e2df;margin:28px 0">

            <p style="color:#94a3b8;font-size:12px;margin:0 0 8px">Pesan asli Anda:</p>
            <blockquote style="border-left:3px solid #cbd5e1;padding:8px 16px;margin:0;color:#64748b;font-size:13px;line-height:1.6">
                {{ $contactMessage->message }}
            </blockquote>
        </div>

        <div style="background:#f8fafc;padding:16px 30px;text-align:center;color:#94a3b8;font-size:12px">
            &copy; {{ date('Y') }} LeadGen Spa. All rights reserved.
        </div>
    </div>
</body>
</html>