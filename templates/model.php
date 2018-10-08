namespace models;

class <?=$mname?> extends Model {

    // 这个模型对应的表
    protected $table = "<?=$tableName?>";
    // 允许接受的字段
    protected $fillable = [ '<?=$fillable?>' ];

}