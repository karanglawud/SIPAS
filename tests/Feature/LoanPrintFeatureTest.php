<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Loan;
use App\Models\Tool;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoanPrintFeatureTest extends TestCase
{
    use RefreshDatabase;

    private function createLoanFixture(): array
    {
        $user = User::factory()->create([
            'role' => 'siswa',
            'identifier' => '0081234567',
            'class_or_dept' => 'XII RPL B',
        ]);

        $admin = User::factory()->create([
            'role' => 'admin',
            'category_scope' => 'all',
        ]);

        $category = Category::create([
            'slug' => 'bengkel',
            'name' => 'Bengkel & Mesin',
            'badge' => 'Otomotif',
            'pj_name' => 'Bpk. Bambang',
            'pj_role_title' => 'PJ Bengkel',
            'description' => 'Alat bengkel',
            'icon' => 'wrench',
            'color' => 'amber',
        ]);

        $tool = Tool::create([
            'category_id' => $category->id,
            'code' => 'BKL-LAS-01',
            'name' => 'Mesin Las Inverter',
            'spec' => 'MMA 160A',
            'location' => 'Bengkel 1',
            'total_stock' => 5,
            'available_stock' => 5,
            'fine_per_day' => 2000,
            'max_days' => 3,
            'image_gradient' => 'from-amber-500 to-orange-600',
        ]);

        $loan = Loan::create([
            'ticket_number' => 'SIPAS-2026-101',
            'user_id' => $user->id,
            'tool_id' => $tool->id,
            'quantity' => 1,
            'start_date' => now()->toDateString(),
            'duration_days' => 2,
            'end_date' => now()->addDays(2)->toDateString(),
            'purpose' => 'Praktik pengelasan',
            'status' => 'disetujui',
            'assigned_units' => ['BKL-LAS-01-U01'],
        ]);

        return [$user, $admin, $loan];
    }

    public function test_guest_is_redirected_to_login_when_accessing_print_routes(): void
    {
        $response = $this->get(route('loans.history.print'));
        $response->assertRedirect(route('login'));
    }

    public function test_user_can_print_their_own_loan_ticket(): void
    {
        [$user, , $loan] = $this->createLoanFixture();

        $response = $this->actingAs($user)->get(route('loans.print', $loan->id));

        $response->assertOk();
        $response->assertSee('SURAT BUKTI PEMINJAMAN ALAT SEKOLAH');
        $response->assertSee($loan->ticket_number);
        $response->assertSee($user->name);
    }

    public function test_user_can_print_their_full_loan_history(): void
    {
        [$user, , $loan] = $this->createLoanFixture();

        $response = $this->actingAs($user)->get(route('loans.history.print'));

        $response->assertOk();
        $response->assertSee('REKAPITULASI RIWAYAT PEMINJAMAN ALAT');
        $response->assertSee($loan->ticket_number);
        $response->assertSee($user->name);
    }

    public function test_admin_can_print_loan_ticket_and_report(): void
    {
        [, $admin, $loan] = $this->createLoanFixture();

        $ticketResponse = $this->actingAs($admin)->get(route('admin.loans.print', $loan->id));
        $ticketResponse->assertOk();
        $ticketResponse->assertSee($loan->ticket_number);

        $reportResponse = $this->actingAs($admin)->get(route('admin.loans.report.print'));
        $reportResponse->assertOk();
        $reportResponse->assertSee('LAPORAN REKAPITULASI TRANSAKSI PEMINJAMAN ALAT');
        $reportResponse->assertSee($loan->ticket_number);
    }
}
