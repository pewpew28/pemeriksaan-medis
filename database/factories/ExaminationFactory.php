<?php

namespace Database\Factories;

use App\Models\Examination;
use App\Models\Patient;
use App\Models\ServiceItem;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class ExaminationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Examination::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Pastikan Patient dan ServiceItem selalu tersedia.
        // Jika tidak ada di DB, buat yang baru.
        $patient = Patient::inRandomOrder()->first();
        if (!$patient) {
            // Asumsi UserFactory akan membuat User dan PatientFactory akan membuat Patient
            // atau Anda memiliki seeder yang memastikan patient dibuat
            $patient = Patient::factory()->create();
        }

        $serviceItem = ServiceItem::inRandomOrder()->first();
        if (!$serviceItem) {
            // Karena kita tidak menggunakan ServiceItemFactory,
            // ini akan mengandalkan ServiceItemsSeeder untuk menyediakan data.
            // Jika seeder belum dijalankan, ini akan gagal.
            // Opsional: tambahkan logika untuk menampilkan error atau membuat default jika benar-benar tidak ada
            // throw new \Exception('No ServiceItem found. Please run ServiceItemsSeeder.');
            // Untuk demo, kita bisa buat dummy jika tidak ada, tapi sebaiknya service item selalu di-seed.
            $serviceItem = ServiceItem::create([
                'name' => 'Default Examination',
                'description' => 'A default service item created by factory fallback.',
                'price' => 100000.00,
                'is_active' => true,
            ]);
        }

        // Tentukan tanggal jadwal di masa depan (misal: 1-60 hari dari sekarang)
        $scheduledDate = $this->faker->dateTimeBetween('now', '+60 days')->format('Y-m-d');

        // Tentukan waktu jadwal (misal: antara 08:00 sampai 17:00, dengan interval 30 menit)
        $availableTimes = [];
        for ($i = 8; $i <= 17; $i++) {
            $availableTimes[] = sprintf('%02d:00:00', $i);
            if ($i < 17) { // Hindari 17:30 jika klinik tutup jam 17:00
                $availableTimes[] = sprintf('%02d:30:00', $i);
            }
        }
        $scheduledTime = $this->faker->randomElement($availableTimes);

        // Tentukan apakah ada permintaan penjemputan secara acak (misal: 40% kemungkinan)
        $pickupRequested = $this->faker->boolean(40);

        $pickupAddress = null;
        $pickupLocationMap = null;
        $pickupTime = null;

        if ($pickupRequested) {
            $pickupAddress = $this->faker->address();
            // Contoh link Google Maps dummy
            $pickupLocationMap = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($pickupAddress);
            // Waktu penjemputan bisa sebelum atau saat jadwal pemeriksaan, lebih realistis jika sebelum
            $pickupTime = Carbon::parse($scheduledTime)->subMinutes($this->faker->randomElement([30, 60, 90]))->format('H:i:s');
            // Pastikan waktu penjemputan tidak negatif
            if (Carbon::parse($pickupTime)->lt(Carbon::parse('07:00:00'))) {
                $pickupTime = Carbon::parse('07:00:00')->format('H:i:s'); // Minimal jam 7 pagi
            }
        }

        // Tentukan status pemeriksaan secara acak
        $statusOptions = [
            'created', // Initial state when examination is first recorded
            'pending_payment',
            'pending_cash_payment',
            'paid',
            'expired_payment',
            'scheduled',
            'in_progress', // New state for when examination is actually happening
            'completed',
            'cancelled'
        ];
        $status = $this->faker->randomElement($statusOptions);

        // Tentukan status hasil pemeriksaan berdasarkan status utama
        $resultAvailable = false;
        if ($status === 'completed') {
            $resultAvailable = $this->faker->boolean(85); // 85% kemungkinan hasil tersedia jika completed
        }

        // Tentukan status pembayaran secara acak
        $paymentStatusOptions = ['pending', 'paid'];
        $paymentStatus = $this->faker->randomElement($paymentStatusOptions);
        $paymentMethod = null;
        if ($paymentStatus === 'paid') {
            $paymentMethod = $this->faker->randomElement(['Bank Transfer', 'Credit Card', 'Cash', 'E-Wallet']);
        }

        return [
            'patient_id' => $patient->id,
            'service_item_id' => $serviceItem->id,
            'scheduled_date' => $scheduledDate,
            'scheduled_time' => $scheduledTime,
            'pickup_requested' => $pickupRequested,
            'pickup_address' => $pickupAddress,
            'pickup_location_map' => $pickupLocationMap,
            'pickup_time' => $pickupTime,
            'status' => $status,
            'notes' => $this->faker->optional(0.4)->sentence(5, true), // 40% kemungkinan ada catatan, 5 kata
            'result_available' => $resultAvailable,
            'payment_status' => $paymentStatus,
            'payment_method' => $paymentMethod,
            'final_price' => $serviceItem->price, // Mengambil harga dari service item yang dipilih
        ];
    }

    /**
     * State: Indicate that the examination is in a pending status.
     * Payment is pending, result not available.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function pending(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'pending',
                'result_available' => false,
                'payment_status' => 'pending',
            ];
        });
    }

    /**
     * State: Indicate that the examination is in a scheduled status.
     * Payment can be pending or paid, result not available.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function scheduled(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'scheduled',
                'result_available' => false,
                'payment_status' => $this->faker->randomElement(['pending', 'paid']),
            ];
        });
    }

    /**
     * State: Indicate that the examination is in a completed status.
     * Payment is always paid, result is always available.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function completed(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'completed',
                'result_available' => true,
                'payment_status' => 'paid',
            ];
        });
    }

    /**
     * State: Indicate that the examination is in a cancelled status.
     * Payment can be pending or paid, result not available.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function cancelled(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'cancelled',
                'result_available' => false,
                'payment_status' => $this->faker->randomElement(['pending', 'paid']),
            ];
        });
    }

    /**
     * State: Indicate that the examination is overdue (scheduled in the past, but not completed/cancelled).
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function overdue(): Factory
    {
        return $this->state(function (array $attributes) {
            // Tanggal jadwal di masa lalu, tapi bukan "completed" atau "cancelled"
            $scheduledDate = $this->faker->dateTimeBetween('-60 days', '-1 days')->format('Y-m-d');
            $statusOptions = ['pending', 'scheduled']; // Hanya status yang memungkinkan overdue
            $status = $this->faker->randomElement($statusOptions);

            return [
                'scheduled_date' => $scheduledDate,
                'status' => $status,
                'result_available' => false,
            ];
        });
    }
}
