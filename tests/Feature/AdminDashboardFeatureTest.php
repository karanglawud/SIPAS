<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Loan;
use App\Models\Message;
use App\Models\Tool;
use App\Models\ToolUnit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminDashboardFeatureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Create the standard test category.
     */
    private function createCategory(string $slug = 'lab-komputer', string $name = 'Lab RPL & Elektronika'): Category
    {
        return Category::create([
            'slug' => $slug,
            'name' => $name,
            'badge' => 'RPL SMKN 5',
            'pj_name' => 'Ibu Rina',
            'pj_role_title' => 'PJ Lab RPL',
            'description' => 'Peralatan Lab RPL',
            'icon' => 'cpu',
            'color' => 'blue',
        ]);
    }

    /**
     * Create a tool with physical units.
     *
     * @return array{tool: Tool, units: ToolUnit[]}
     */
    private function createToolWithUnits(Category $category, int $stock = 3): array
    {
        $tool = Tool::create([
            'category_id' => $category->id,
            'code' => 'LAB-OSC-01',
            'name' => 'Digital Oscilloscope 100MHz',
            'spec' => 'Siglent SDS1104X-E',
            'location' => 'Lab RPL 1',
            'total_stock' => $stock,
            'available_stock' => $stock,
            'max_days' => 3,
            'fine_per_day' => 10000,
            'image_gradient' => 'from-blue-600 to-indigo-700',
        ]);

        $units = [];
        for ($i = 1; $i <= $stock; $i++) {
            $units[] = ToolUnit::create([
                'tool_id' => $tool->id,
                'unit_code' => 'LAB-OSC-01-U'.str_pad((string) $i, 2, '0', STR_PAD_LEFT),
                'status' => 'tersedia',
                'condition' => 'baik',
                'notes' => 'Unit perdana terdata',
            ]);
        }

        return ['tool' => $tool, 'units' => $units];
    }

    /**
     * Create an admin user.
     */
    private function createAdmin(string $scope = 'all'): User
    {
        return User::factory()->create([
            'role' => 'admin',
            'category_scope' => $scope,
            'class_or_dept' => 'PJ Lab RPL',
        ]);
    }

    /**
     * Create a borrower (student) user.
     */
    private function createBorrower(): User
    {
        return User::factory()->create([
            'role' => 'siswa',
            'class_or_dept' => 'XII RPL B',
            'identifier' => '0087654321',
            'phone' => '08123456789',
        ]);
    }

    /**
     * Create a loan with a given status.
     */
    private function createLoan(User $user, Tool $tool, string $status = 'menunggu', array $extra = []): Loan
    {
        return Loan::create(array_merge([
            'ticket_number' => 'SIPAS-2026-'.random_int(100, 999),
            'user_id' => $user->id,
            'tool_id' => $tool->id,
            'quantity' => 1,
            'start_date' => now()->toDateString(),
            'duration_days' => 2,
            'end_date' => now()->addDays(2)->toDateString(),
            'purpose' => 'Praktikum Pengujian IoT',
            'status' => $status,
        ], $extra));
    }

    // ─── Tool Management Tests ────────────────────────────────────────

    public function test_admin_can_access_tool_management_page(): void
    {
        $admin = $this->createAdmin();
        $category = $this->createCategory();
        $this->createToolWithUnits($category);

        $response = $this->actingAs($admin)->get(route('admin.tools.index'));
        $response->assertStatus(200);
        $response->assertSee('Digital Oscilloscope 100MHz');
    }

    public function test_admin_can_create_new_tool_with_auto_generated_units(): void
    {
        $admin = $this->createAdmin();
        $category = $this->createCategory();

        $response = $this->actingAs($admin)->post(route('admin.tools.store'), [
            'category_id' => $category->id,
            'code' => 'LAB-MUL-01',
            'name' => 'Digital Multimeter',
            'spec' => 'Fluke 87V',
            'location' => 'Lab RPL 2',
            'total_stock' => 3,
            'fine_per_day' => 5000,
            'max_days' => 7,
            'image_gradient' => 'from-green-600 to-emerald-700',
        ]);

        $response->assertRedirect(route('admin.tools.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('tools', [
            'code' => 'LAB-MUL-01',
            'name' => 'Digital Multimeter',
            'total_stock' => 3,
            'available_stock' => 3,
        ]);

        // Verify 3 ToolUnits auto-generated
        $tool = Tool::where('code', 'LAB-MUL-01')->first();
        $this->assertNotNull($tool);
        $this->assertCount(3, $tool->units);
        $this->assertDatabaseHas('tool_units', ['unit_code' => 'LAB-MUL-01-U01']);
        $this->assertDatabaseHas('tool_units', ['unit_code' => 'LAB-MUL-01-U02']);
        $this->assertDatabaseHas('tool_units', ['unit_code' => 'LAB-MUL-01-U03']);
    }

    public function test_admin_can_update_tool_details(): void
    {
        $admin = $this->createAdmin();
        $category = $this->createCategory();
        $data = $this->createToolWithUnits($category);

        $response = $this->actingAs($admin)->put(route('admin.tools.update', $data['tool']->id), [
            'name' => 'Oscilloscope Updated',
            'code' => 'LAB-OSC-01',
            'spec' => 'Updated spec',
            'location' => 'Lab RPL 3',
            'fine_per_day' => 15000,
            'max_days' => 5,
            'image_gradient' => 'from-red-600 to-rose-700',
        ]);

        $response->assertRedirect(route('admin.tools.index'));
        $response->assertSessionHas('success');

        $data['tool']->refresh();
        $this->assertEquals('Oscilloscope Updated', $data['tool']->name);
        $this->assertEquals('Lab RPL 3', $data['tool']->location);
        $this->assertEquals(15000, $data['tool']->fine_per_day);
    }

    public function test_admin_can_create_and_update_tool_with_image(): void
    {
        Storage::fake('public');

        $admin = $this->createAdmin();
        $category = $this->createCategory();

        $file = UploadedFile::fake()->image('test_tool.jpg', 400, 400);

        $response = $this->actingAs($admin)->post(route('admin.tools.store'), [
            'category_id' => $category->id,
            'code' => 'LAB-CAM-99',
            'name' => 'Kamera Uji',
            'spec' => 'Kamera Praktik',
            'location' => 'Lab 1',
            'total_stock' => 2,
            'fine_per_day' => 5000,
            'max_days' => 3,
            'image' => $file,
        ]);

        $response->assertRedirect(route('admin.tools.index'));
        $tool = Tool::where('code', 'LAB-CAM-99')->first();
        $this->assertNotNull($tool);
        $this->assertNotNull($tool->image);
        Storage::disk('public')->assertExists($tool->image);

        // Update with new image
        $newFile = UploadedFile::fake()->image('updated_tool.png', 400, 400);
        $updateResponse = $this->actingAs($admin)->put(route('admin.tools.update', $tool->id), [
            'name' => 'Kamera Uji Updated',
            'code' => 'LAB-CAM-99',
            'location' => 'Lab 2',
            'fine_per_day' => 6000,
            'max_days' => 4,
            'image' => $newFile,
        ]);

        $updateResponse->assertRedirect(route('admin.tools.index'));
        $tool->refresh();
        $this->assertNotNull($tool->image);
        Storage::disk('public')->assertExists($tool->image);

        // Update with remove_image checkbox
        $removeResponse = $this->actingAs($admin)->put(route('admin.tools.update', $tool->id), [
            'name' => 'Kamera Uji Tanpa Foto',
            'code' => 'LAB-CAM-99',
            'location' => 'Lab 2',
            'fine_per_day' => 6000,
            'max_days' => 4,
            'remove_image' => '1',
        ]);

        $removeResponse->assertRedirect(route('admin.tools.index'));
        $tool->refresh();
        $this->assertNull($tool->image);
    }

    public function test_admin_can_add_unit_to_existing_tool(): void
    {
        $admin = $this->createAdmin();
        $category = $this->createCategory();
        $data = $this->createToolWithUnits($category, 2);

        $response = $this->actingAs($admin)->post(route('admin.tools.units.store', $data['tool']->id), [
            'unit_code' => 'LAB-OSC-01-EXTRA',
            'condition' => 'baik',
            'notes' => 'Unit tambahan dari donor',
        ]);

        $response->assertSessionHas('success');

        $data['tool']->refresh();
        $this->assertEquals(3, $data['tool']->total_stock);
        $this->assertEquals(3, $data['tool']->available_stock);
        $this->assertDatabaseHas('tool_units', ['unit_code' => 'LAB-OSC-01-EXTRA']);
    }

    public function test_admin_can_add_unit_without_notes_and_auto_code(): void
    {
        $admin = $this->createAdmin();
        $category = $this->createCategory();
        $data = $this->createToolWithUnits($category, 2);

        $response = $this->actingAs($admin)->post(route('admin.tools.units.store', $data['tool']->id), [
            'condition' => 'baik',
        ]);

        $response->assertSessionHas('success');

        $data['tool']->refresh();
        $this->assertEquals(3, $data['tool']->total_stock);
        $this->assertEquals(3, $data['tool']->available_stock);
        $this->assertDatabaseHas('tool_units', [
            'tool_id' => $data['tool']->id,
            'unit_code' => $data['tool']->code.'-U03',
            'notes' => null,
        ]);
    }

    public function test_admin_cannot_delete_tool_with_active_loans(): void
    {
        $admin = $this->createAdmin();
        $category = $this->createCategory();
        $data = $this->createToolWithUnits($category);
        $borrower = $this->createBorrower();

        $this->createLoan($borrower, $data['tool'], 'menunggu');

        $response = $this->actingAs($admin)->delete(route('admin.tools.destroy', $data['tool']->id));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('tools', ['id' => $data['tool']->id]);
    }

    public function test_admin_can_delete_tool_without_active_loans(): void
    {
        $admin = $this->createAdmin();
        $category = $this->createCategory();
        $data = $this->createToolWithUnits($category, 1);

        $response = $this->actingAs($admin)->delete(route('admin.tools.destroy', $data['tool']->id));

        $response->assertRedirect(route('admin.tools.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('tools', ['id' => $data['tool']->id]);
    }

    public function test_admin_can_delete_available_unit(): void
    {
        $admin = $this->createAdmin();
        $category = $this->createCategory();
        $data = $this->createToolWithUnits($category, 2);

        $unit = $data['units'][1];

        $response = $this->actingAs($admin)->delete(route('admin.tools.units.destroy', $unit->id));
        $response->assertSessionHas('success');

        $data['tool']->refresh();
        $this->assertEquals(1, $data['tool']->total_stock);
        $this->assertEquals(1, $data['tool']->available_stock);
    }

    public function test_admin_cannot_delete_borrowed_unit(): void
    {
        $admin = $this->createAdmin();
        $category = $this->createCategory();
        $data = $this->createToolWithUnits($category, 1);

        $data['units'][0]->update(['status' => 'dipinjam']);

        $response = $this->actingAs($admin)->delete(route('admin.tools.units.destroy', $data['units'][0]->id));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('tool_units', ['id' => $data['units'][0]->id]);
    }

    // ─── Loan Verification Tests ──────────────────────────────────────

    public function test_admin_can_access_loan_verification_page(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get(route('admin.loans.index'));
        $response->assertStatus(200);
    }

    public function test_admin_can_approve_loan_with_auto_unit_assignment(): void
    {
        $admin = $this->createAdmin();
        $category = $this->createCategory();
        $data = $this->createToolWithUnits($category, 2);
        $borrower = $this->createBorrower();

        $loan = $this->createLoan($borrower, $data['tool'], 'menunggu');

        $response = $this->actingAs($admin)->post(route('admin.loans.approve', $loan->id));

        $response->assertSessionHas('success');

        $loan->refresh();
        $this->assertEquals('disetujui', $loan->status);
        $this->assertNotEmpty($loan->assigned_units);
        $this->assertCount(1, $loan->assigned_units);
        $this->assertEquals($admin->id, $loan->approved_by);

        // Unit status should be changed
        $data['units'][0]->refresh();
        $this->assertEquals('dipinjam', $data['units'][0]->status);

        // Available stock decremented
        $data['tool']->refresh();
        $this->assertEquals(1, $data['tool']->available_stock);

        // Notification message created
        $this->assertDatabaseHas('messages', [
            'recipient_id' => $borrower->id,
            'loan_id' => $loan->id,
            'type' => 'approval',
        ]);
    }

    public function test_admin_can_approve_loan_with_specific_unit_selection(): void
    {
        $admin = $this->createAdmin();
        $category = $this->createCategory();
        $data = $this->createToolWithUnits($category, 3);
        $borrower = $this->createBorrower();

        $loan = $this->createLoan($borrower, $data['tool'], 'menunggu');

        // Choose the second unit specifically
        $response = $this->actingAs($admin)->post(route('admin.loans.approve', $loan->id), [
            'unit_codes' => ['LAB-OSC-01-U02'],
        ]);

        $response->assertSessionHas('success');

        $loan->refresh();
        $this->assertEquals('disetujui', $loan->status);
        $this->assertEquals(['LAB-OSC-01-U02'], $loan->assigned_units);

        // The second unit (U02) should be 'dipinjam', U01 and U03 remain 'tersedia'
        $data['units'][0]->refresh();
        $this->assertEquals('tersedia', $data['units'][0]->status);
        $data['units'][1]->refresh();
        $this->assertEquals('dipinjam', $data['units'][1]->status);
        $data['units'][2]->refresh();
        $this->assertEquals('tersedia', $data['units'][2]->status);
    }

    public function test_approve_fails_when_insufficient_units(): void
    {
        $admin = $this->createAdmin();
        $category = $this->createCategory();
        $data = $this->createToolWithUnits($category, 1);
        $borrower = $this->createBorrower();

        // Mark the only unit as unavailable
        $data['units'][0]->update(['status' => 'rusak']);

        $loan = $this->createLoan($borrower, $data['tool'], 'menunggu');

        $response = $this->actingAs($admin)->post(route('admin.loans.approve', $loan->id));
        $response->assertSessionHas('error');

        $loan->refresh();
        $this->assertEquals('menunggu', $loan->status);
    }

    public function test_admin_can_handover_approved_loan(): void
    {
        $admin = $this->createAdmin();
        $category = $this->createCategory();
        $data = $this->createToolWithUnits($category);
        $borrower = $this->createBorrower();

        $loan = $this->createLoan($borrower, $data['tool'], 'disetujui', [
            'assigned_units' => ['LAB-OSC-01-U01'],
            'approved_by' => $admin->id,
            'approved_at' => now(),
            'pj_notes' => 'Disetujui',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.loans.handover', $loan->id), [
            'handover_notes' => 'Diserahkan di Lab RPL 1',
        ]);

        $response->assertSessionHas('success');

        $loan->refresh();
        $this->assertEquals('dipinjam', $loan->status);
        $this->assertStringContainsString('Serah Terima', $loan->pj_notes);

        // Notification sent
        $this->assertDatabaseHas('messages', [
            'recipient_id' => $borrower->id,
            'loan_id' => $loan->id,
            'type' => 'info',
        ]);
    }

    public function test_handover_fails_for_non_approved_loan(): void
    {
        $admin = $this->createAdmin();
        $category = $this->createCategory();
        $data = $this->createToolWithUnits($category);
        $borrower = $this->createBorrower();

        $loan = $this->createLoan($borrower, $data['tool'], 'menunggu');

        $response = $this->actingAs($admin)->post(route('admin.loans.handover', $loan->id));
        $response->assertSessionHas('error');

        $loan->refresh();
        $this->assertEquals('menunggu', $loan->status);
    }

    public function test_admin_can_reject_loan_with_reason(): void
    {
        $admin = $this->createAdmin();
        $category = $this->createCategory();
        $data = $this->createToolWithUnits($category);
        $borrower = $this->createBorrower();

        $loan = $this->createLoan($borrower, $data['tool'], 'menunggu');

        $response = $this->actingAs($admin)->post(route('admin.loans.reject', $loan->id), [
            'reject_reason' => 'Stok habis untuk minggu ini',
        ]);

        $response->assertSessionHas('info');

        $loan->refresh();
        $this->assertEquals('ditolak', $loan->status);
        $this->assertStringContainsString('Stok habis', $loan->pj_notes);
        $this->assertEquals($admin->id, $loan->approved_by);

        $this->assertDatabaseHas('messages', [
            'recipient_id' => $borrower->id,
            'loan_id' => $loan->id,
            'type' => 'warning',
        ]);
    }

    public function test_reject_requires_reason(): void
    {
        $admin = $this->createAdmin();
        $category = $this->createCategory();
        $data = $this->createToolWithUnits($category);
        $borrower = $this->createBorrower();

        $loan = $this->createLoan($borrower, $data['tool'], 'menunggu');

        $response = $this->actingAs($admin)->post(route('admin.loans.reject', $loan->id), [
            'reject_reason' => '',
        ]);

        $response->assertSessionHasErrors('reject_reason');

        $loan->refresh();
        $this->assertEquals('menunggu', $loan->status);
    }

    public function test_admin_can_complete_loan_with_good_condition(): void
    {
        $admin = $this->createAdmin();
        $category = $this->createCategory();
        $data = $this->createToolWithUnits($category, 2);
        $borrower = $this->createBorrower();

        // Mark first unit as borrowed
        $data['units'][0]->update(['status' => 'dipinjam']);

        $loan = $this->createLoan($borrower, $data['tool'], 'dipinjam', [
            'assigned_units' => ['LAB-OSC-01-U01'],
            'approved_by' => $admin->id,
            'approved_at' => now(),
            'pj_notes' => 'Disetujui | Serah Terima: OK',
        ]);

        // Update available_stock to reflect the borrowed unit
        $data['tool']->update(['available_stock' => 1]);

        $response = $this->actingAs($admin)->post(route('admin.loans.complete', $loan->id), [
            'return_condition' => 'baik',
            'fine_status' => 'none',
            'return_notes' => 'Alat dalam kondisi baik',
        ]);

        $response->assertSessionHas('success');

        $loan->refresh();
        $this->assertEquals('selesai', $loan->status);
        $this->assertEquals('baik', $loan->return_condition);

        // Unit restored to tersedia
        $data['units'][0]->refresh();
        $this->assertEquals('tersedia', $data['units'][0]->status);
        $this->assertEquals('baik', $data['units'][0]->condition);

        // Available stock restored
        $data['tool']->refresh();
        $this->assertEquals(2, $data['tool']->available_stock);

        $this->assertDatabaseHas('messages', [
            'recipient_id' => $borrower->id,
            'loan_id' => $loan->id,
            'type' => 'info',
        ]);
    }

    public function test_admin_can_complete_loan_with_damaged_condition(): void
    {
        $admin = $this->createAdmin();
        $category = $this->createCategory();
        $data = $this->createToolWithUnits($category, 1);
        $borrower = $this->createBorrower();

        $data['units'][0]->update(['status' => 'dipinjam']);
        $data['tool']->update(['available_stock' => 0]);

        $loan = $this->createLoan($borrower, $data['tool'], 'dipinjam', [
            'assigned_units' => ['LAB-OSC-01-U01'],
            'approved_by' => $admin->id,
            'approved_at' => now(),
            'pj_notes' => 'Disetujui',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.loans.complete', $loan->id), [
            'return_condition' => 'rusak',
            'fine_status' => 'belum_lunas',
            'return_notes' => 'Layar retak',
        ]);

        $response->assertSessionHas('success');

        $loan->refresh();
        $this->assertEquals('selesai', $loan->status);
        $this->assertEquals('rusak', $loan->return_condition);

        // Damaged unit NOT restored to available
        $data['units'][0]->refresh();
        $this->assertEquals('rusak', $data['units'][0]->status);

        $data['tool']->refresh();
        $this->assertEquals(0, $data['tool']->available_stock);
    }

    // ─── Per-Loan Messaging Tests ─────────────────────────────────────

    public function test_admin_can_send_quick_message_for_specific_loan(): void
    {
        $admin = $this->createAdmin();
        $category = $this->createCategory();
        $data = $this->createToolWithUnits($category);
        $borrower = $this->createBorrower();

        $loan = $this->createLoan($borrower, $data['tool'], 'dipinjam');

        $response = $this->actingAs($admin)->post(route('admin.loans.sendMessage', $loan->id), [
            'type' => 'warning',
            'message' => 'Harap segera kembalikan alat, sudah melewati batas waktu.',
        ]);

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('messages', [
            'sender_id' => $admin->id,
            'recipient_id' => $borrower->id,
            'loan_id' => $loan->id,
            'type' => 'warning',
        ]);
    }

    // ─── Admin Message Center Tests ───────────────────────────────────

    public function test_admin_can_access_message_center(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get(route('admin.messages.index'));
        $response->assertStatus(200);
    }

    public function test_admin_can_send_message_to_borrower(): void
    {
        $admin = $this->createAdmin();
        $category = $this->createCategory();
        $borrower = $this->createBorrower();

        $response = $this->actingAs($admin)->post(route('admin.messages.store'), [
            'recipient_id' => $borrower->id,
            'category_id' => $category->id,
            'type' => 'info',
            'message' => 'Silakan ambil alat di Lab RPL 1 pukul 09.00 WIB.',
        ]);

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('messages', [
            'sender_id' => $admin->id,
            'recipient_id' => $borrower->id,
            'category_id' => $category->id,
            'type' => 'info',
            'is_read' => false,
        ]);
    }

    public function test_admin_can_send_message_linked_to_loan(): void
    {
        $admin = $this->createAdmin();
        $category = $this->createCategory();
        $data = $this->createToolWithUnits($category);
        $borrower = $this->createBorrower();
        $loan = $this->createLoan($borrower, $data['tool'], 'dipinjam');

        $response = $this->actingAs($admin)->post(route('admin.messages.store'), [
            'recipient_id' => $borrower->id,
            'category_id' => $category->id,
            'loan_id' => $loan->id,
            'type' => 'warning',
            'message' => 'Mohon segera kembalikan alat. Terima kasih.',
        ]);

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('messages', [
            'sender_id' => $admin->id,
            'recipient_id' => $borrower->id,
            'loan_id' => $loan->id,
            'type' => 'warning',
        ]);
    }

    // ─── Authorization Guard Tests ────────────────────────────────────

    public function test_non_admin_cannot_access_tool_management(): void
    {
        $borrower = $this->createBorrower();

        $response = $this->actingAs($borrower)->get(route('admin.tools.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_non_admin_cannot_access_loan_verification(): void
    {
        $borrower = $this->createBorrower();

        $response = $this->actingAs($borrower)->get(route('admin.loans.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_non_admin_cannot_access_message_center(): void
    {
        $borrower = $this->createBorrower();

        $response = $this->actingAs($borrower)->get(route('admin.messages.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_non_admin_cannot_approve_loan(): void
    {
        $admin = $this->createAdmin();
        $category = $this->createCategory();
        $data = $this->createToolWithUnits($category);
        $borrower = $this->createBorrower();
        $loan = $this->createLoan($borrower, $data['tool'], 'menunggu');

        $response = $this->actingAs($borrower)->post(route('admin.loans.approve', $loan->id));
        $response->assertRedirect(route('login'));

        $loan->refresh();
        $this->assertEquals('menunggu', $loan->status);
    }

    public function test_guest_cannot_access_admin_routes(): void
    {
        $response = $this->get(route('admin.tools.index'));
        $response->assertRedirect(route('login'));

        $response = $this->get(route('admin.loans.index'));
        $response->assertRedirect(route('login'));

        $response = $this->get(route('admin.messages.index'));
        $response->assertRedirect(route('login'));
    }

    // ─── Category Scope Tests ─────────────────────────────────────────

    public function test_scoped_admin_only_sees_own_category_tools(): void
    {
        $category1 = $this->createCategory('lab-komputer', 'Lab RPL');
        $category2 = $this->createCategory('bengkel', 'Bengkel Mesin');

        $scopedAdmin = $this->createAdmin('lab-komputer');

        Tool::create([
            'category_id' => $category1->id,
            'code' => 'LAB-PC-01',
            'name' => 'Komputer PC Lab',
            'spec' => 'i7 16GB',
            'location' => 'Lab RPL 1',
            'total_stock' => 10,
            'available_stock' => 10,
            'max_days' => 3,
            'fine_per_day' => 10000,
            'image_gradient' => 'from-blue-600 to-indigo-700',
        ]);

        Tool::create([
            'category_id' => $category2->id,
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

        $response = $this->actingAs($scopedAdmin)->get(route('admin.tools.index'));
        $response->assertStatus(200);
        $response->assertSee('Komputer PC Lab');
        $response->assertDontSee('Mesin Las Inverter');
    }

    public function test_scoped_admin_cannot_edit_other_category_tool(): void
    {
        $category1 = $this->createCategory('lab-komputer', 'Lab RPL');
        $category2 = $this->createCategory('bengkel', 'Bengkel Mesin');

        $scopedAdmin = $this->createAdmin('lab-komputer');

        $otherTool = Tool::create([
            'category_id' => $category2->id,
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

        $response = $this->actingAs($scopedAdmin)->get(route('admin.tools.edit', $otherTool->id));
        $response->assertStatus(403);
    }

    // ─── Unit Status Update Test ──────────────────────────────────────

    public function test_admin_can_update_unit_status_and_stock_recalculates(): void
    {
        $admin = $this->createAdmin();
        $category = $this->createCategory();
        $data = $this->createToolWithUnits($category, 3);

        // Mark unit as damaged
        $response = $this->actingAs($admin)->patch(route('admin.tools.units.update', $data['units'][0]->id), [
            'status' => 'rusak',
            'condition' => 'rusak berat',
            'notes' => 'Layar pecah',
        ]);

        $response->assertSessionHas('success');

        $data['units'][0]->refresh();
        $this->assertEquals('rusak', $data['units'][0]->status);
        $this->assertEquals('rusak berat', $data['units'][0]->condition);

        // Available stock should recalculate (3 total, 1 rusak = 2 available)
        $data['tool']->refresh();
        $this->assertEquals(2, $data['tool']->available_stock);
    }

    // ─── Scope Authorization On Action Methods ─────────────────────────

    public function test_scoped_admin_cannot_approve_other_category_loan(): void
    {
        $category1 = $this->createCategory('lab-komputer', 'Lab RPL');
        $category2 = $this->createCategory('bengkel', 'Bengkel Mesin');

        $scopedAdmin = $this->createAdmin('lab-komputer');
        $borrower = $this->createBorrower();
        $otherData = $this->createToolWithUnits($category2, 2);
        $loan = $this->createLoan($borrower, $otherData['tool'], 'menunggu');

        $response = $this->actingAs($scopedAdmin)->post(route('admin.loans.approve', $loan->id));
        $response->assertStatus(403);
    }

    public function test_scoped_admin_cannot_reject_other_category_loan(): void
    {
        $category1 = $this->createCategory('lab-komputer', 'Lab RPL');
        $category2 = $this->createCategory('bengkel', 'Bengkel Mesin');

        $scopedAdmin = $this->createAdmin('lab-komputer');
        $borrower = $this->createBorrower();
        $otherData = $this->createToolWithUnits($category2, 2);
        $loan = $this->createLoan($borrower, $otherData['tool'], 'menunggu');

        $response = $this->actingAs($scopedAdmin)->post(route('admin.loans.reject', $loan->id), [
            'reject_reason' => 'Tidak memenuhi syarat',
        ]);
        $response->assertStatus(403);
    }

    public function test_scoped_admin_cannot_handover_other_category_loan(): void
    {
        $category1 = $this->createCategory('lab-komputer', 'Lab RPL');
        $category2 = $this->createCategory('bengkel', 'Bengkel Mesin');

        $scopedAdmin = $this->createAdmin('lab-komputer');
        $borrower = $this->createBorrower();
        $otherData = $this->createToolWithUnits($category2, 2);
        $loan = $this->createLoan($borrower, $otherData['tool'], 'disetujui');

        $response = $this->actingAs($scopedAdmin)->post(route('admin.loans.handover', $loan->id));
        $response->assertStatus(403);
    }

    public function test_scoped_admin_cannot_complete_other_category_loan(): void
    {
        $category1 = $this->createCategory('lab-komputer', 'Lab RPL');
        $category2 = $this->createCategory('bengkel', 'Bengkel Mesin');

        $scopedAdmin = $this->createAdmin('lab-komputer');
        $borrower = $this->createBorrower();
        $otherData = $this->createToolWithUnits($category2, 2);
        $loan = $this->createLoan($borrower, $otherData['tool'], 'dipinjam');

        $response = $this->actingAs($scopedAdmin)->post(route('admin.loans.complete', $loan->id), [
            'return_condition' => 'baik',
            'fine_status' => 'none',
        ]);
        $response->assertStatus(403);
    }

    public function test_scoped_admin_cannot_print_other_category_ticket(): void
    {
        $category1 = $this->createCategory('lab-komputer', 'Lab RPL');
        $category2 = $this->createCategory('bengkel', 'Bengkel Mesin');

        $scopedAdmin = $this->createAdmin('lab-komputer');
        $borrower = $this->createBorrower();
        $otherData = $this->createToolWithUnits($category2, 2);
        $loan = $this->createLoan($borrower, $otherData['tool'], 'disetujui');

        $response = $this->actingAs($scopedAdmin)->get(route('admin.loans.print', $loan->id));
        $response->assertStatus(403);
    }

    public function test_scoped_admin_cannot_manage_other_category_tool_units(): void
    {
        $category1 = $this->createCategory('lab-komputer', 'Lab RPL');
        $category2 = $this->createCategory('bengkel', 'Bengkel Mesin');

        $scopedAdmin = $this->createAdmin('lab-komputer');
        $otherData = $this->createToolWithUnits($category2, 2);

        // Cannot add unit
        $response = $this->actingAs($scopedAdmin)->post(route('admin.tools.units.store', $otherData['tool']->id), [
            'condition' => 'baik',
        ]);
        $response->assertStatus(403);

        // Cannot update unit
        $response = $this->actingAs($scopedAdmin)->patch(route('admin.tools.units.update', $otherData['units'][0]->id), [
            'status' => 'rusak',
            'condition' => 'rusak berat',
        ]);
        $response->assertStatus(403);

        // Cannot destroy unit
        $response = $this->actingAs($scopedAdmin)->delete(route('admin.tools.units.destroy', $otherData['units'][0]->id));
        $response->assertStatus(403);

        // Cannot destroy tool
        $response = $this->actingAs($scopedAdmin)->delete(route('admin.tools.destroy', $otherData['tool']->id));
        $response->assertStatus(403);
    }

    // ─── Status Guard Tests ───────────────────────────────────────────

    public function test_cannot_approve_loan_that_is_already_approved(): void
    {
        $admin = $this->createAdmin();
        $category = $this->createCategory();
        $data = $this->createToolWithUnits($category, 3);
        $borrower = $this->createBorrower();
        $loan = $this->createLoan($borrower, $data['tool'], 'disetujui');

        $response = $this->actingAs($admin)->post(route('admin.loans.approve', $loan->id));
        $response->assertSessionHas('error');
    }

    public function test_cannot_approve_loan_that_is_rejected_or_cancelled(): void
    {
        $admin = $this->createAdmin();
        $category = $this->createCategory();
        $data = $this->createToolWithUnits($category, 3);
        $borrower = $this->createBorrower();

        $rejectedLoan = $this->createLoan($borrower, $data['tool'], 'ditolak');
        $response = $this->actingAs($admin)->post(route('admin.loans.approve', $rejectedLoan->id));
        $response->assertSessionHas('error');

        $cancelledLoan = $this->createLoan($borrower, $data['tool'], 'dibatalkan');
        $response = $this->actingAs($admin)->post(route('admin.loans.approve', $cancelledLoan->id));
        $response->assertSessionHas('error');
    }

    public function test_cannot_reject_loan_that_is_not_menunggu(): void
    {
        $admin = $this->createAdmin();
        $category = $this->createCategory();
        $data = $this->createToolWithUnits($category, 3);
        $borrower = $this->createBorrower();
        $loan = $this->createLoan($borrower, $data['tool'], 'disetujui');

        $response = $this->actingAs($admin)->post(route('admin.loans.reject', $loan->id), [
            'reject_reason' => 'Alasan penolakan',
        ]);
        $response->assertSessionHas('error');
    }

    public function test_cannot_complete_loan_that_is_not_dipinjam(): void
    {
        $admin = $this->createAdmin();
        $category = $this->createCategory();
        $data = $this->createToolWithUnits($category, 3);
        $borrower = $this->createBorrower();
        $loan = $this->createLoan($borrower, $data['tool'], 'menunggu');

        $response = $this->actingAs($admin)->post(route('admin.loans.complete', $loan->id), [
            'return_condition' => 'baik',
            'fine_status' => 'none',
        ]);
        $response->assertSessionHas('error');
    }

    public function test_cannot_approve_when_stock_is_insufficient(): void
    {
        $admin = $this->createAdmin();
        $category = $this->createCategory();
        $data = $this->createToolWithUnits($category, 1);
        $borrower = $this->createBorrower();

        // Loan requests 2 units, but available stock is only 1
        $loan = $this->createLoan($borrower, $data['tool'], 'menunggu', ['quantity' => 2]);

        $response = $this->actingAs($admin)->post(route('admin.loans.approve', $loan->id));
        $response->assertSessionHas('error');

        $loan->refresh();
        $this->assertEquals('menunggu', $loan->status);
        $this->assertEquals(1, $data['tool']->fresh()->available_stock);
    }
}
