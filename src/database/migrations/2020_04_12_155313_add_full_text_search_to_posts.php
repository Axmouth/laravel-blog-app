<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddFullTextSearchToPosts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            DB::statement("ALTER TABLE posts ADD COLUMN searchtext TSVECTOR");
            DB::statement("UPDATE posts SET searchtext = to_tsvector('english', title || ' ' || body)");
            DB::statement("CREATE INDEX searchtext_gin ON posts USING GIN(searchtext)");
            DB::statement("CREATE TRIGGER ts_searchtext BEFORE INSERT OR UPDATE ON posts FOR EACH ROW EXECUTE PROCEDURE tsvector_update_trigger('searchtext', 'pg_catalog.english', 'title', 'body')");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            DB::statement("DROP TRIGGER IF EXISTS tsvector_update_trigger ON posts");
            DB::statement("DROP INDEX IF EXISTS searchtext_gin");
            DB::statement("ALTER TABLE posts DROP COLUMN searchtext");
        });
    }
}
