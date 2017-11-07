<?php
namespace Shopex\LubanAdmin\Models;

use Illuminate\Database\Eloquent\Model;
use File;

class Generator extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'generator';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['model_title', 'crud_name', 'model_type', 'model_namespace', 'controller_namespace', 'route_group', 'view_path', 'is_route', 'is_view', 'is_controller', 'is_migration', 'fields', 'files', 'staging'];

    public function checkFile()
    {
        $files = json_decode($this->files,1);
        if (is_array($files)) {
            if (isset($files['controller'])) {
                $files['controller']['real_path'] = app_path($files['controller']['path']);
                $files['controller']['now_hash'] =  $this->hashFile($files['controller']['real_path']);
            }
            if (isset($files['controller'])) {
                $files['model']['real_path'] = app_path($files['model']['path']);
                $files['model']['now_hash'] =  $this->hashFile($files['model']['real_path']);
            }
            if (isset($files['migration'])) {
                $files['migration']['real_path'] = database_path('migrations').'/'.$files['migration']['path'];
                $files['migration']['now_hash'] =  $this->hashFile($files['migration']['real_path']);
            }
            if (isset($files['view'])) {
                $viewDirectory = config('view.paths')[0] . '/';
                foreach ($files['view'] as $key => &$value) {
                    $value['real_path'] = $viewDirectory.$value['path'];
                    $value['now_hash'] = $this->hashFile($value['real_path']);
                }
            }
            
        }
        return $files;
    }
    public function hashFile($path){
        if (File::exists($path)) {
            return File::hash($path);
        }
        return '';
    }
    public function removeAllFile()
    {
        $file = $this->checkFile();
        if (is_array($file) && $file) {
            foreach ($file as $type => $row) {
                if (isset($row['hash']) && $row['hash'] ) {
                    $this->removeOrBakFile($row);
                }else{
                    foreach ($row as $viewrow) {
                        $this->removeOrBakFile($viewrow);
                    }
                }
            }
        }
        
    }
    /*
        $row['path']
        $row['real_path']
        $row['hash']
        $row['now_hash']
     */
    public function removeOrBakFile($row)
    {
        if (File::exists($row['real_path'])) {
            if ($row['hash'] === $row['now_hash']) {
                File::delete($row['real_path']);
            }else{
                File::move($row['real_path'],$row['real_path'].'.'.time());
            }
        }
    }
}

















