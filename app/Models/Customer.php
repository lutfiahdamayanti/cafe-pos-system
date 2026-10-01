<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'birth_date',
        'visit_count',
        'total_spending',
        'tier',
        'points',
        'referral_code',
        'referred_by_id',
        'address',
        'notes',
        'last_visit',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'last_visit' => 'datetime',
            'total_spending' => 'float',
            'points' => 'integer',
            'visit_count' => 'integer',
        ];
    }

    /**
     * Relasi ke riwayat pemesanan berdasarkan nomor telepon
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'phone', 'phone')->latest();
    }

    /**
     * Relasi ke riwayat poin pelanggan
     */
    public function pointLogs()
    {
        return $this->hasMany(CustomerPointLog::class)->latest();
    }

    public function pointClaims()
    {
        return $this->hasMany(PointRewardClaim::class)->latest();
    }

    public function stamps()
    {
        return $this->hasMany(CustomerStamp::class);
    }

    public function referrals()
    {
        return $this->hasMany(Referral::class, 'referrer_id')->latest();
    }

    public function referredBy()
    {
        return $this->belongsTo(Customer::class, 'referred_by_id');
    }

    public function cashbackLogs()
    {
        return $this->hasMany(CashbackLog::class)->latest();
    }

    public function getReferralCode(): string
    {
        if (!$this->referral_code) {
            $prefix = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $this->name), 0, 4));
            if (strlen($prefix) < 3) $prefix = 'CAFE';
            $code = 'REF-' . $prefix . $this->id . rand(10, 99);
            $this->update(['referral_code' => $code]);
            return $code;
        }
        return $this->referral_code;
    }

    /**
     * Hitung tier otomatis berdasarkan total belanja & jumlah kunjungan
     */
    public function calculateTier(): string
    {
        $spending = (float) $this->total_spending;
        $visits = (int) $this->visit_count;

        if ($spending >= 3000000 || $visits >= 30) {
            return 'Platinum';
        }
        if ($spending >= 1500000 || $visits >= 15) {
            return 'Gold';
        }
        if ($spending >= 500000 || $visits >= 5) {
            return 'Silver';
        }
        return 'Bronze';
    }

    /**
     * Warna / style badge membership tier
     */
    public function getTierBadgeAttribute(): array
    {
        return match ($this->tier) {
            'Platinum' => [
                'name' => 'Platinum',
                'bg' => '#6f42c1',
                'color' => '#ffffff',
                'icon' => 'bi-gem',
                'class' => 'bg-purple text-white'
            ],
            'Gold' => [
                'name' => 'Gold',
                'bg' => '#ffc107',
                'color' => '#212529',
                'icon' => 'bi-award-fill',
                'class' => 'bg-warning text-dark'
            ],
            'Silver' => [
                'name' => 'Silver',
                'bg' => '#adb5bd',
                'color' => '#212529',
                'icon' => 'bi-shield-shaded',
                'class' => 'bg-secondary text-white'
            ],
            default => [
                'name' => 'Bronze',
                'bg' => '#cd7f32',
                'color' => '#ffffff',
                'icon' => 'bi-shield',
                'class' => 'text-white'
            ],
        };
    }

    /**
     * Menu favorit yang paling sering dipesan oleh pelanggan ini
     */
    public function favoriteMenus($limit = 5)
    {
        return DB::table('order_details')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->join('menus', 'order_details.menu_id', '=', 'menus.id')
            ->where('orders.phone', $this->phone)
            ->where('orders.status', '!=', 'Cancelled')
            ->select(
                'menus.id',
                'menus.name',
                'menus.image',
                'menus.price',
                DB::raw('SUM(order_details.qty) as total_qty'),
                DB::raw('SUM(order_details.total) as total_spent'),
                DB::raw('COUNT(DISTINCT orders.id) as order_count')
            )
            ->groupBy('menus.id', 'menus.name', 'menus.image', 'menus.price')
            ->orderByDesc('total_qty')
            ->limit($limit)
            ->get();
    }

    /**
     * Status apakah pelanggan berulang tahun hari ini
     */
    public function isBirthdayToday(): bool
    {
        if (!$this->birth_date) {
            return false;
        }
        $today = Carbon::today();
        return $this->birth_date->format('m-d') === $today->format('m-d');
    }

    /**
     * Scope pelanggan yang ulang tahun hari ini
     */
    public function scopeBirthdayToday($query)
    {
        $today = Carbon::today();
        return $query->whereNotNull('birth_date')
            ->whereMonth('birth_date', $today->month)
            ->whereDay('birth_date', $today->day);
    }

    /**
     * Scope pelanggan yang ulang tahun bulan ini
     */
    public function scopeBirthdayThisMonth($query)
    {
        $today = Carbon::today();
        return $query->whereNotNull('birth_date')
            ->whereMonth('birth_date', $today->month);
    }
}