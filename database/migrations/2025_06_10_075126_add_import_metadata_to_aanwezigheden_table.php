<?PHP

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('aanwezigheden', function (Blueprint $table) {
            $table->string('import_filename')->nullable()->after('kleur');
            $table->foreignId('import_log_id')->nullable()->constrained('import_logs')->nullOnDelete()->after('import_filename');
        });
    }

    public function down(): void
    {
        Schema::table('aanwezigheden', function (Blueprint $table) {
            $table->dropForeign(['import_log_id']);
            $table->dropColumn(['import_filename', 'import_log_id']);
        });
    }
};
