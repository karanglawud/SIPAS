<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Loan;
use App\Models\Tool;
use App\Models\ToolUnit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationAndLoanFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_registration_with_dropdown_fields_creates_expected_class(): void
    {
        $response = $this->post(route('register.post'), [
            'name' => 'Ahmad Siswa',
            'email' => 'ahmad@smkn5solo.sch.id',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'role' => 'siswa',
            'identifier' => '0087654321',
            'grade' => 'XII',
            'major' => 'RPL',
            'subclass' => 'B',
            'phone' => '08123456789',
        ]);

        $response->assertRedirect(route('catalog.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'ahmad@smkn5solo.sch.id',
            'role' => 'siswa',
            'identifier' => '0087654321',
            'class_or_dept' => 'XII RPL B',
        ]);
    }

    public function test_teacher_registration_uses_class_or_dept_field(): void
    {
        $response = $this->post(route('register.post'), [
            'name' => 'Budi Guru',
            'email' => 'budi@smkn5solo.sch.id',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'role' => 'guru',
            'identifier' => '198203152008011003',
            'class_or_dept' => 'Guru Produktif RPL',
            'phone' => '08129876543',
        ]);

        $response->assertRedirect(route('catalog.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'budi@smkn5solo.sch.id',
            'role' => 'guru',
            'identifier' => '198203152008011003',
            'class_or_dept' => 'Guru Produktif RPL',
        ]);
    }

    public function test_navigation_bar_does_not_contain_loan_form_link(): void
    {
        $response = $this->get(route('home'));
        $response->assertStatus(200);
        $response->assertDontSee('Formulir Pinjam');
    }

    public function test_accessing_loan_create_without_tool_redirects_to_catalog(): void
    {
        $user = User::factory()->create(['role' => 'siswa', 'class_or_dept' => 'XII RPL B']);

        $response = $this->actingAs($user)->get(route('loans.create'));
        $response->assertRedirect(route('catalog.index'));
        $response->assertSessionHas('info');
    }

    public function test_accessing_loan_create_with_tool_shows_form_for_that_tool(): void
    {
        $user = User::factory()->create(['role' => 'siswa', 'class_or_dept' => 'XII RPL B']);
        $category = Category::create([
            'slug' => 'lab-komputer',
            'name' => 'Lab RPL & Elektronika',
            'badge' => 'RPL SMKN 5',
            'pj_name' => 'Ibu Rina',
            'pj_role_title' => 'PJ Lab RPL',
            'description' => 'Peralatan Lab RPL',
            'icon' => 'cpu',
            'color' => 'blue',
        ]);
        $tool = Tool::create([
            'category_id' => $category->id,
            'code' => 'LAB-OSC-01',
            'name' => 'Digital Oscilloscope 100MHz',
            'spec' => 'Spesifikasi Lab',
            'location' => 'Lab RPL 1',
            'total_stock' => 5,
            'available_stock' => 5,
            'max_days' => 3,
            'fine_per_day' => 10000,
            'image_gradient' => 'from-blue-600 to-indigo-700',
        ]);

        $response = $this->actingAs($user)->get(route('loans.create', $tool->id));
        $response->assertStatus(200);
        $response->assertSee('Digital Oscilloscope 100MHz');
        $response->assertSee('LAB-OSC-01');
    }

    public function test_user_can_cancel_pending_loan(): void
    {
        $user = User::factory()->create(['role' => 'siswa', 'class_or_dept' => 'XII RPL B']);
        $category = Category::create([
            'slug' => 'lab-komputer',
            'name' => 'Lab RPL & Elektronika',
            'badge' => 'RPL SMKN 5',
            'pj_name' => 'Ibu Rina',
            'pj_role_title' => 'PJ Lab RPL',
            'description' => 'Peralatan Lab RPL',
            'icon' => 'cpu',
            'color' => 'blue',
        ]);
        $tool = Tool::create([
            'category_id' => $category->id,
            'code' => 'LAB-OSC-01',
            'name' => 'Digital Oscilloscope 100MHz',
            'spec' => 'Spesifikasi Lab',
            'location' => 'Lab RPL 1',
            'total_stock' => 5,
            'available_stock' => 5,
            'max_days' => 3,
            'fine_per_day' => 10000,
            'image_gradient' => 'from-blue-600 to-indigo-700',
        ]);

        $loan = Loan::create([
            'ticket_number' => 'SIPAS-2026-999',
            'user_id' => $user->id,
            'tool_id' => $tool->id,
            'quantity' => 1,
            'start_date' => now()->toDateString(),
            'duration_days' => 2,
            'end_date' => now()->addDays(2)->toDateString(),
            'purpose' => 'Praktikum Pengujian IoT',
            'status' => 'menunggu',
        ]);

        $response = $this->actingAs($user)->post(route('loans.cancel', $loan->id));
        $response->assertSessionHas('success');

        $loan->refresh();
        $this->assertEquals('dibatalkan', $loan->status);
    }

    public function test_cancelling_approved_loan_restores_tool_units_and_stock(): void
    {
        $user = User::factory()->create(['role' => 'siswa', 'class_or_dept' => 'XII RPL B']);
        $category = Category::create([
            'slug' => 'bengkel',
            'name' => 'Bengkel Mesin',
            'badge' => 'Mesin SMKN 5',
            'pj_name' => 'Bpk Bambang',
            'pj_role_title' => 'PJ Bengkel',
            'description' => 'Peralatan Bengkel',
            'icon' => 'wrench',
            'color' => 'amber',
        ]);
        $tool = Tool::create([
            'category_id' => $category->id,
            'code' => 'BKL-LAS-01',
            'name' => 'Mesin Las Inverter',
            'spec' => '160A',
            'location' => 'Bengkel 1',
            'total_stock' => 2,
            'available_stock' => 1, // Decremented because 1 is approved
            'max_days' => 3,
            'fine_per_day' => 10000,
            'image_gradient' => 'from-amber-600 to-yellow-600',
        ]);

        $unit = ToolUnit::create([
            'tool_id' => $tool->id,
            'unit_code' => 'BKL-LAS-01-U01',
            'serial_number' => 'SN-LAS-001',
            'status' => 'dipinjam',
            'condition' => 'baik',
        ]);

        $loan = Loan::create([
            'ticket_number' => 'SIPAS-2026-888',
            'user_id' => $user->id,
            'tool_id' => $tool->id,
            'quantity' => 1,
            'start_date' => now()->toDateString(),
            'duration_days' => 2,
            'end_date' => now()->addDays(2)->toDateString(),
            'purpose' => 'Praktik Bengkel',
            'status' => 'disetujui',
            'assigned_units' => ['BKL-LAS-01-U01'],
        ]);

        $response = $this->actingAs($user)->post(route('loans.cancel', $loan->id));
        $response->assertSessionHas('success');

        $loan->refresh();
        $this->assertEquals('dibatalkan', $loan->status);

        $unit->refresh();
        $this->assertEquals('tersedia', $unit->status);

        $tool->refresh();
        $this->assertEquals(2, $tool->available_stock);
    }

    public function test_user_cannot_cancel_another_users_loan(): void
    {
        $user1 = User::factory()->create(['role' => 'siswa', 'class_or_dept' => 'XII RPL B']);
        $user2 = User::factory()->create(['role' => 'siswa', 'class_or_dept' => 'XI TP A']);
        $category = Category::create([
            'slug' => 'bengkel',
            'name' => 'Bengkel Mesin',
            'badge' => 'Mesin SMKN 5',
            'pj_name' => 'Bpk Bambang',
            'pj_role_title' => 'PJ Bengkel',
            'description' => 'Peralatan Bengkel',
            'icon' => 'wrench',
            'color' => 'amber',
        ]);
        $tool = Tool::create([
            'category_id' => $category->id,
            'code' => 'BKL-LAS-01',
            'name' => 'Mesin Las Inverter',
            'spec' => '160A',
            'location' => 'Bengkel 1',
            'total_stock' => 2,
            'available_stock' => 2,
            'max_days' => 3,
            'fine_per_day' => 10000,
            'image_gradient' => 'from-amber-600 to-yellow-600',
        ]);

        $loan = Loan::create([
            'ticket_number' => 'SIPAS-2026-777',
            'user_id' => $user1->id,
            'tool_id' => $tool->id,
            'quantity' => 1,
            'start_date' => now()->toDateString(),
            'duration_days' => 2,
            'end_date' => now()->addDays(2)->toDateString(),
            'purpose' => 'Praktik Bengkel',
            'status' => 'menunggu',
        ]);

        $response = $this->actingAs($user2)->post(route('loans.cancel', $loan->id));
        $response->assertStatus(403);

        $loan->refresh();
        $this->assertEquals('menunggu', $loan->status);
    }

    public function test_guest_cannot_access_borrower_routes(): void
    {
        $response = $this->get(route('loans.index'));
        $response->assertRedirect(route('login'));

        $response = $this->get(route('loans.create'));
        $response->assertRedirect(route('login'));

        $response = $this->get(route('messages.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_ticket_number_generation_does_not_collide_when_previous_loan_deleted(): void
    {
        $user = User::factory()->create(['role' => 'siswa', 'class_or_dept' => 'XII RPL B']);
        $category = Category::create([
            'slug' => 'lab-komputer',
            'name' => 'Lab RPL & Elektronika',
            'badge' => 'RPL SMKN 5',
            'pj_name' => 'Ibu Rina',
            'pj_role_title' => 'PJ Lab RPL',
            'description' => 'Peralatan Lab RPL',
            'icon' => 'cpu',
            'color' => 'blue',
        ]);
        $tool = Tool::create([
            'category_id' => $category->id,
            'code' => 'LAB-OSC-01',
            'name' => 'Digital Oscilloscope 100MHz',
            'spec' => 'Spesifikasi Lab',
            'location' => 'Lab RPL 1',
            'total_stock' => 10,
            'available_stock' => 10,
            'max_days' => 3,
            'fine_per_day' => 10000,
            'image_gradient' => 'from-blue-600 to-indigo-700',
        ]);

        // Submit first loan -> will get SIPAS-YYYY-101
        $this->actingAs($user)->post(route('loans.store'), [
            'tool_id' => $tool->id,
            'quantity' => 1,
            'start_date' => now()->toDateString(),
            'duration_days' => 2,
            'purpose' => 'Pengujian 1',
        ]);
        $loan1 = Loan::first();
        $this->assertNotNull($loan1);

        // Submit second loan -> will get SIPAS-YYYY-102
        $this->actingAs($user)->post(route('loans.store'), [
            'tool_id' => $tool->id,
            'quantity' => 1,
            'start_date' => now()->toDateString(),
            'duration_days' => 2,
            'purpose' => 'Pengujian 2',
        ]);
        $loan2 = Loan::latest('id')->first();

        // Delete loan1 (simulating deletion/cascade)
        $loan1->delete();

        // Count is now 1. If formula was count() + 101, it would yield 102 (collision with loan2!).
        // Our collision-safe generator should assign 103 without error.
        $response = $this->actingAs($user)->post(route('loans.store'), [
            'tool_id' => $tool->id,
            'quantity' => 1,
            'start_date' => now()->toDateString(),
            'duration_days' => 2,
            'purpose' => 'Pengujian 3',
        ]);
        $response->assertRedirect(route('loans.index'));
        $response->assertSessionHas('success');

        $loan3 = Loan::latest('id')->first();
        $this->assertNotEquals($loan2->ticket_number, $loan3->ticket_number);
    }
}
