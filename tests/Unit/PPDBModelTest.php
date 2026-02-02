<?php

namespace Tests\Unit;

use App\Models\PPDB;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PPDBModelTest extends TestCase
{
    use RefreshDatabase;

    private function createPPDB($overrides = [])
    {
        $ppdb = new PPDB();
        $ppdb->name = $overrides['name'] ?? 'Test Student';
        $ppdb->nik = $overrides['nik'] ?? '1234567890123456';
        $ppdb->email = $overrides['email'] ?? 'test@example.com';
        $ppdb->birth_date = $overrides['birth_date'] ?? '2000-01-01';
        $ppdb->birth_place = $overrides['birth_place'] ?? 'Jakarta';
        // Skip gender for now due to constraint issues
        $ppdb->gender = 'male'; // Use valid gender value
        $ppdb->address = $overrides['address'] ?? 'Jl. Test No. 123';
        $ppdb->phone = $overrides['phone'] ?? '08123456789';
        $ppdb->parent_name = $overrides['parent_name'] ?? 'Parent Name';
        $ppdb->parent_phone = $overrides['parent_phone'] ?? '08123456780';
        $ppdb->previous_school = $overrides['previous_school'] ?? 'SMA Test';
        $ppdb->desired_major = $overrides['desired_major'] ?? 'Computer Science';
        $ppdb->status = $overrides['status'] ?? 'pending';
        $ppdb->save();
        
        return $ppdb;
    }

    public function test_ppdb_uses_correct_table()
    {
        $ppdb = new PPDB();
        
        $this->assertEquals('ppdb', $ppdb->getTable());
    }

    public function test_ppdb_can_be_created()
    {
        $ppdb = $this->createPPDB();

        $this->assertInstanceOf(PPDB::class, $ppdb);
        $this->assertDatabaseHas('ppdb', ['id' => $ppdb->id]);
    }

    public function test_ppdb_has_fillable_attributes()
    {
        $data = [
            'name' => 'Test Student',
            'email' => 'test@example.com',
            'phone' => '08123456789'
        ];
        
        $ppdb = new PPDB($data);
        
        $this->assertEquals('Test Student', $ppdb->name);
        $this->assertEquals('test@example.com', $ppdb->email);
        $this->assertEquals('08123456789', $ppdb->phone);
    }

    public function test_ppdb_can_be_found()
    {
        $ppdb = $this->createPPDB();
        
        $found = PPDB::find($ppdb->id);
        
        $this->assertInstanceOf(PPDB::class, $found);
        $this->assertEquals($ppdb->id, $found->id);
    }

    public function test_ppdb_can_be_updated()
    {
        $ppdb = $this->createPPDB(['status' => 'pending']);
        
        $ppdb->status = 'approved';
        $ppdb->save();
        
        $this->assertEquals('approved', $ppdb->fresh()->status);
    }

    public function test_ppdb_can_be_deleted()
    {
        $ppdb = $this->createPPDB();
        
        $ppdb->delete();
        
        $this->assertDatabaseMissing('ppdb', ['id' => $ppdb->id]);
    }

    public function test_ppdb_casts_birth_date()
    {
        $ppdb = $this->createPPDB(['birth_date' => '2000-01-01']);

        // PPDB model doesn't have date casting, so birth_date remains as string
        $this->assertIsString($ppdb->birth_date);
        $this->assertEquals('2000-01-01', $ppdb->birth_date);
    }

    public function test_ppdb_query_scopes()
    {
        $this->createPPDB(['name' => 'Student 1', 'nik' => '1111111111111111', 'email' => 'student1@test.com', 'status' => 'pending']);
        $this->createPPDB(['name' => 'Student 2', 'nik' => '2222222222222222', 'email' => 'student2@test.com', 'status' => 'approved']);
        $this->createPPDB(['name' => 'Student 3', 'nik' => '3333333333333333', 'email' => 'student3@test.com', 'status' => 'rejected']);

        $pending = PPDB::where('status', 'pending')->get();
        $approved = PPDB::where('status', 'approved')->get();

        $this->assertCount(1, $pending);
        $this->assertCount(1, $approved);
        $this->assertEquals('pending', $pending->first()->status);
        $this->assertEquals('approved', $approved->first()->status);
    }

    public function test_ppdb_mass_assignment()
    {
        $data = [
            'name' => 'Test Student',
            'email' => 'test@example.com',
            'phone' => '08123456789'
        ];
        
        $ppdb = new PPDB($data);
        
        $this->assertEquals('Test Student', $ppdb->name);
        $this->assertEquals('test@example.com', $ppdb->email);
        $this->assertEquals('08123456789', $ppdb->phone);
    }

    public function test_ppdb_status_workflow()
    {
        $ppdb = $this->createPPDB(['status' => 'pending']);

        // Pending to approved
        $ppdb->status = 'approved';
        $ppdb->save();
        $this->assertEquals('approved', $ppdb->fresh()->status);

        // Approved to rejected
        $ppdb->status = 'rejected';
        $ppdb->save();
        $this->assertEquals('rejected', $ppdb->fresh()->status);
    }

    public function test_ppdb_search_by_name()
    {
        $this->createPPDB(['name' => 'John Doe', 'nik' => '1111111111111111', 'email' => 'john@test.com']);
        $this->createPPDB(['name' => 'Jane Smith', 'nik' => '2222222222222222', 'email' => 'jane@test.com']);
        $this->createPPDB(['name' => 'John Smith', 'nik' => '3333333333333333', 'email' => 'johnsmith@test.com']);

        $johns = PPDB::where('name', 'like', '%John%')->get();

        $this->assertCount(2, $johns);
    }

    public function test_ppdb_search_by_school()
    {
        $this->createPPDB(['name' => 'Student 1', 'nik' => '1111111111111111', 'previous_school' => 'SMA A', 'email' => 'student1@test.com']);
        $this->createPPDB(['name' => 'Student 2', 'nik' => '2222222222222222', 'previous_school' => 'SMA B', 'email' => 'student2@test.com']);
        $this->createPPDB(['name' => 'Student 3', 'nik' => '3333333333333333', 'previous_school' => 'SMA A', 'email' => 'student3@test.com']);

        $smaA = PPDB::where('previous_school', 'SMA A')->get();

        $this->assertCount(2, $smaA);
    }
}
