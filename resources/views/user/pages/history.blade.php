@extends('user.layouts.main')

@section('title', 'Booking History — LeadGen Spa')
@section('footer_type', 'grid')

@push('styles')
<style>
    .page-title {
        padding: 46px 0 8px;
        max-width: 1180px;
        margin: 0 auto;
        padding-left: 24px;
        padding-right: 24px;
    }
    .back-btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 6px 16px rgba(0,0,0,.08);
        margin-bottom: 22px;
        font-size: 14px;
        color: var(--ink);
        transition: .25s;
        text-decoration: none;
    }
    .back-btn:hover { background: var(--primary); color: #fff; }
    .page-title h1 {
        font-size: 26px;
        font-weight: 800;
        margin-bottom: 8px;
    }
    .page-title p { color: var(--gray); font-size: 13.5px; }

    .history-wrap {
        max-width: 1180px;
        margin: 0 auto;
        padding: 20px 24px 90px;
    }

    .history-list {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .history-card {
        background: #fff;
        border-radius: 16px;
        padding: 24px 28px;
        box-shadow: 0 5px 20px rgba(0,0,0,.04);
        border: 1px solid var(--line);
        transition: all .25s ease;
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 20px;
        align-items: center;
    }
    .history-card:hover {
        border-color: var(--primary);
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(0,0,0,.08);
    }
    .history-card.pending-card {
        border-left: 4px solid #d97706;
        background: linear-gradient(90deg, #fffbf0 0%, #fff 10%);
    }

    .hc-code {
        font-family: monospace;
        font-weight: 800;
        font-size: 15px;
        color: var(--primary);
        margin-bottom: 6px;
        letter-spacing: .5px;
    }
    .hc-meta {
        font-size: 13px;
        color: var(--gray);
        line-height: 1.6;
        margin-bottom: 6px;
    }
    .hc-meta i {
        width: 14px;
        color: #b7aca9;
        margin-right: 6px;
    }
    .hc-items {
        font-size: 12.5px;
        color: #5b5654;
        margin-top: 8px;
    }
    .hc-items strong { color: var(--ink); }

    .hc-right {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 10px;
    }
    .hc-total {
        font-size: 18px;
        font-weight: 800;
        color: var(--ink);
    }
    .hc-status {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .5px;
        padding: 5px 12px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .hc-status i { font-size: 10px; }
    .hc-status.pending { background: rgba(245,158,11,.15); color: #d97706; }
    .hc-status.paid { background: rgba(34,197,94,.15); color: #16a34a; }
    .hc-status.completed { background: rgba(34,197,94,.15); color: #16a34a; }
    .hc-status.cancelled { background: rgba(239,68,68,.15); color: #dc2626; }
    .hc-status.no-show { background: rgba(100,116,139,.15); color: #64748b; }
    .hc-status.expired { background: rgba(100,116,139,.15); color: #64748b; }

    .hc-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: 2px;
    }

    .hc-btn {
        font-size: 12px;
        font-weight: 700;
        color: var(--primary);
        text-transform: uppercase;
        letter-spacing: .5px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: .25s;
        background: none;
        border: none;
        cursor: pointer;
        padding: 0;
    }
    .hc-btn:hover { color: var(--primary-dark); gap: 10px; }

    .hc-pay-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        border-radius: 99px;
        background: #d97706;
        color: #fff !important;
        text-decoration: none;
        font-size: 11.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .5px;
        transition: all .2s;
        border: none;
        cursor: pointer;
        box-shadow: 0 4px 10px -3px rgba(217, 119, 6, .5);
    }
    .hc-pay-btn:hover {
        background: #b45309;
        transform: translateY(-1px);
        box-shadow: 0 6px 14px -3px rgba(217, 119, 6, .6);
    }
    .hc-pay-btn i { font-size: 11px; }

    .empty-state {
        text-align: center;
        padding: 80px 20px;
        background: #fff;
        border-radius: 16px;
        border: 1px solid var(--line);
    }
    .empty-state i {
        font-size: 48px;
        color: #ddd;
        margin-bottom: 20px;
    }
    .empty-state h3 {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 10px;
        color: var(--ink);
    }
    .empty-state p {
        color: var(--gray);
        font-size: 14px;
        margin-bottom: 24px;
    }
    .btn-primary {
        display: inline-block;
        background: var(--primary);
        color: #fff;
        padding: 12px 28px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 1px;
        text-transform: uppercase;
        text-decoration: none;
        transition: all 0.4s ease;
    }
    .btn-primary:hover {
        background: var(--primary-dark);
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(247, 108, 108, 0.3);
    }

    /* ===== PAGINATION ===== */
    .pagination-wrap {
        margin-top: 30px;
        display: flex;
        justify-content: center;
    }
    .fe-pagination { display: flex; align-items: center; justify-content: center; gap: 6px; flex-wrap: wrap; }
    .fe-page-btn { width: 38px; height: 38px; border-radius: 50%; background: #fff; border: 1.5px solid var(--line); display: flex; align-items: center; justify-content: center; color: var(--ink); font-size: 13px; text-decoration: none; transition: all .2s; }
    .fe-page-btn:hover:not(.disabled) { background: var(--primary); color: #fff; border-color: var(--primary); transform: translateY(-2px); }
    .fe-page-btn.disabled { opacity: .35; cursor: not-allowed; }

    .fe-page-num { min-width: 38px; height: 38px; padding: 0 12px; border-radius: 50%; background: #fff; border: 1.5px solid var(--line); display: flex; align-items: center; justify-content: center; color: var(--ink); font-size: 13.5px; font-weight: 600; text-decoration: none; transition: all .2s; }
    .fe-page-num:hover { background: var(--primary); color: #fff; border-color: var(--primary); transform: translateY(-2px); }
    .fe-page-num.active { background: var(--primary); color: #fff; border-color: var(--primary); font-weight: 800; cursor: default; }

    .fe-page-dots { color: var(--gray); font-weight: 600; padding: 0 4px; }

    @media (max-width: 700px) {
        .history-card {
            grid-template-columns: 1fr;
            padding: 20px 22px;
        }
        .hc-right {
            align-items: flex-start;
            flex-direction: column;
            width: 100%;
            border-top: 1px dashed var(--line);
            padding-top: 14px;
        }
        .hc-actions {
            width: 100%;
            justify-content: space-between;
        }
    }
</style>
@endpush

@section('content')

    <div class="page-title">
        <a href="{{ url('treatments') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1>Booking History</h1>
        <p>View all your booking history here.</p>
    </div>

    <div class="history-wrap">

        @if($bookings->count())
            <div class="history-list">
                @foreach($bookings as $b)
                    @php
                        $statusIcon = [
                            'pending'   => 'fa-clock',
                            'paid'      => 'fa-check-circle',
                            'completed' => 'fa-check-double',
                            'cancelled' => 'fa-times-circle',
                            'no-show'   => 'fa-user-slash',
                            'expired'   => 'fa-hourglass-end',
                        ][$b->status] ?? 'fa-clock';
                    @endphp
                    <div class="history-card {{ $b->status === 'pending' ? 'pending-card' : '' }}">
                        <div>
                            <div class="hc-code">{{ $b->booking_code }}</div>
                            <div class="hc-meta">
                                <i class="far fa-calendar-alt"></i>
                                {{ $b->booking_date->format('l, d F Y') }}
                                &nbsp;·&nbsp;
                                <i class="far fa-clock"></i>
                                {{ $b->booking_time }}
                            </div>
                            <div class="hc-items">
                                <strong>{{ count($b->items ?? []) }} {{ count($b->items ?? []) === 1 ? 'item' : 'items' }}</strong>
                                — {{ implode(', ', array_slice(array_column($b->items, 'name'), 0, 3)) }}
                                @if(count($b->items) > 3) ... @endif
                            </div>
                        </div>

                        <div class="hc-right">
                            <div class="hc-total">
                                Rp {{ number_format($b->payment_amount ?: $b->total_price, 0, ',', '.') }}
                            </div>
                            <span class="hc-status {{ $b->status }}">
                                <i class="fas {{ $statusIcon }}"></i>
                                {{ $b->status_label }}
                            </span>
                            <div class="hc-actions">
                                @if($b->canBePaid())
                                    <form action="{{ route('payment.create', $b->booking_code) }}" method="POST" style="margin:0;">
                                        @csrf
                                        <input type="hidden" name="payment_method" value="{{ $b->payment_method }}">
                                        <input type="hidden" name="payment_method_name" value="{{ $b->payment_method_name }}">
                                        <input type="hidden" name="payment_fee" value="{{ $b->payment_fee }}">
                                        <button type="submit" class="hc-pay-btn">
                                            <i class="fas fa-credit-card"></i> Pay Now
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('history.show', $b->booking_code) }}" class="hc-btn">
                                    View Detail <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($bookings->hasPages())
                <div class="pagination-wrap">
                    {{ $bookings->links('user.partials.pagination') }}
                </div>
            @endif
        @else
            <div class="empty-state">
                <i class="fas fa-calendar-times"></i>
                <h3>No Bookings Yet</h3>
                <p>You haven't made any bookings. Let's book your first treatment now!</p>
                <a href="{{ route('treatments.index') }}" class="btn-primary">
                    View Treatments
                </a>
            </div>
        @endif

    </div>

@endsection