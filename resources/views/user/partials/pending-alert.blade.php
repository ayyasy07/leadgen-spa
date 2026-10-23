@auth
@php
    $pendingAlertBooking = \App\Models\Booking::where('user_id', auth()->id())
        ->where('status', \App\Models\Booking::STATUS_PENDING)
        ->where(function ($q) {
            $q->whereNull('expired_at')
              ->orWhere('expired_at', '>', now());
        })
        ->orderBy('created_at', 'desc')
        ->get();

    $pendingAlertCount = $pendingAlertBooking->count();
    $pendingAlertFirst = $pendingAlertBooking->first();
@endphp

@if($pendingAlertCount > 0)
<style>
    /* ===== PENDING ALERT — TOP, SOLID, SETEMA FE ===== */
    .pending-alert-bar {
        position: fixed;
        top: 84px;
        left: 50%;
        transform: translateX(-50%) translateY(-30px);
        z-index: 9998;
        width: calc(100% - 40px);
        max-width: 720px;

        background: #fff;
        border-radius: 14px;
        padding: 16px 22px;

        box-shadow:
            0 16px 40px -12px rgba(30, 20, 15, .18),
            0 0 0 1px rgba(0, 0, 0, .05),
            0 0 0 3px rgba(247, 108, 108, .12);

        display: flex;
        align-items: center;
        gap: 14px;

        opacity: 0;
        pointer-events: none;
        transition: opacity .35s ease, transform .35s ease;
    }
    .pending-alert-bar.show {
        opacity: 1;
        pointer-events: auto;
        transform: translateX(-50%) translateY(0);
    }
    .pending-alert-bar.hide {
        opacity: 0;
        transform: translateX(-50%) translateY(-30px);
        pointer-events: none;
    }

    /* Icon — solid primary */
    .pending-alert-bar .pa-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        flex: none;
        background: var(--primary);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
        box-shadow: 0 6px 14px -4px rgba(247, 108, 108, .5);
    }

    /* Content */
    .pending-alert-bar .pa-content {
        flex: 1;
        min-width: 0;
    }
    .pending-alert-bar .pa-title {
        font-size: 13.5px;
        font-weight: 800;
        color: #2a2420;
        margin-bottom: 3px;
        line-height: 1.4;
        letter-spacing: -.01em;
    }
    .pending-alert-bar .pa-desc {
        font-size: 12px;
        color: #8a8482;
        line-height: 1.5;
    }

    /* Button — solid primary */
    .pending-alert-bar .pa-btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 10px 18px;
        border-radius: 99px;
        background: var(--primary);
        color: #fff !important;
        text-decoration: none;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .2px;
        white-space: nowrap;
        flex: none;
        transition: all .2s ease;
        box-shadow: 0 6px 14px -4px rgba(247, 108, 108, .5);
    }
    .pending-alert-bar .pa-btn i {
        font-size: 10px;
        transition: transform .2s;
    }
    .pending-alert-bar .pa-btn:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
        box-shadow: 0 10px 20px -4px rgba(247, 108, 108, .6);
    }
    .pending-alert-bar .pa-btn:hover i {
        transform: translateX(2px);
    }

    /* Close */
    .pending-alert-bar .pa-close {
        width: 30px;
        height: 30px;
        padding: 0;
        border: none;
        border-radius: 50%;
        background: #f4f1ef;
        color: #8a8482;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        flex: none;
        transition: all .2s;
    }
    .pending-alert-bar .pa-close:hover {
        background: #e7e2df;
        color: #2a2420;
    }

    @media (max-width: 600px) {
        .pending-alert-bar {
            top: 76px;
            width: calc(100% - 24px);
            padding: 14px 16px;
            gap: 10px;
        }
        .pending-alert-bar .pa-icon {
            width: 36px;
            height: 36px;
            font-size: 14px;
            border-radius: 10px;
        }
        .pending-alert-bar .pa-title {
            font-size: 12.5px;
        }
        .pending-alert-bar .pa-desc {
            font-size: 11px;
        }
        .pending-alert-bar .pa-btn {
            padding: 8px 12px;
            font-size: 11px;
        }
        .pending-alert-bar .pa-btn span {
            display: none;
        }
        .pending-alert-bar .pa-close {
            width: 26px;
            height: 26px;
        }
    }
</style>

<div class="pending-alert-bar" id="pendingAlert">
    <div class="pa-icon">
        <i class="fas fa-clock"></i>
    </div>

    <div class="pa-content">
        <div class="pa-title">
            @if($pendingAlertCount === 1)
                You have 1 booking that is pending payment
            @else
                You have {{ $pendingAlertCount }} bookings that are pending payment
            @endif
        </div>
        <div class="pa-desc">
            Please complete the payment before the booking expires.
        </div>
    </div>

    @if($pendingAlertFirst && $pendingAlertFirst->canBePaid())
        <a href="{{ route('booking.success', $pendingAlertFirst->booking_code) }}" class="pa-btn">
            <span>Pay Now</span>
            <i class="fas fa-arrow-right"></i>
        </a>
    @else
        <a href="{{ url('history') }}" class="pa-btn">
            <span>View History</span>
            <i class="fas fa-arrow-right"></i>
        </a>
    @endif

    <button type="button" class="pa-close" onclick="dismissPendingAlert()" aria-label="Close">
        <i class="fas fa-times"></i>
    </button>
</div>

<script>
    (function() {
        const bar = document.getElementById('pendingAlert');
        if (!bar) return;

        const KEY = 'spaPendingAlertShown';
        const isHome = {{ request()->routeIs('home') ? 'true' : 'false' }};
        const isSuccess = {{ request()->routeIs('booking.success') ? 'true' : 'false' }};

        if (isSuccess) {
            setTimeout(() => bar.classList.add('show'), 500);
        } else if (isHome) {
            if (!sessionStorage.getItem(KEY)) {
                setTimeout(() => bar.classList.add('show'), 900);
                sessionStorage.setItem(KEY, '1');
                setTimeout(() => {
                    bar.classList.remove('show');
                    bar.classList.add('hide');
                }, 7000);
            }
        }

        window.dismissPendingAlert = function() {
            bar.classList.remove('show');
            bar.classList.add('hide');
            sessionStorage.setItem(KEY, '1');
        };
    })();
</script>
@endif
@endauth