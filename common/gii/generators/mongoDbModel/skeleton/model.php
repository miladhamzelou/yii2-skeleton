<?php
/**
 * This is the template for generating the model class of a specified collection.
 */

/* @var $this yii\web\View */
/* @var $generator yii\mongodb\gii\model\Generator */
/* @var $collectionName string full collection name */
/* @var $attributes array list of attribute names */
/* @var $className string class name */
/* @var $labels string[] list of attribute labels (name => label) */
/* @var $rules string[] list of validation rules */

echo "<?php\n";
?>
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) <?= date('Y') ?> Power Kernel
 */

namespace <?= $generator->ns ?>;

use Yii;
use common\behaviors\UTCDateTimeBehavior;

/**
 * This is the model class for collection "<?= $collectionName ?>".
 *
<?php foreach ($attributes as $attribute): ?>
 * @property <?= $attribute == '_id' ? '\MongoDB\BSON\ObjectID|string' : 'mixed' ?> <?= "\${$attribute}\n" ?>
<?php endforeach; ?>
 */
class <?= $className ?> extends <?= '\\' . ltrim($generator->baseClass, '\\') . "\n" ?>
{

    const STATUS_ACTIVE = 'STATUS_ACTIVE';
    const STATUS_INACTIVE = 'STATUS_INACTIVE';

    /**
     * get status list
     * @param null $e
     * @return array
     */
    public static function getStatusOption($e = null)
    {
        $option = [
            self::STATUS_ACTIVE => <?= $generator->enableI18N?"Yii::\$app->getModule('$generator->messageCategory')->t('Active')":"'Active'" ?>,
            self::STATUS_INACTIVE => <?= $generator->enableI18N?"Yii::\$app->getModule('$generator->messageCategory')->t('Inactive')":"'Inactive'" ?>,
        ];
        if (is_array($e))
            foreach ($e as $i)
                unset($option[$i]);
        return $option;
    }

    /**
     * get status text
     * @return string
     */
    public function getStatusText()
    {
        $status=$this->status;
        $list=self::getStatusOption();
        if(!empty($status) && in_array($status, array_keys($list))){
            return $list[$status];
        }
        return Yii::$app->getModule('<?= $generator->messageCategory ?>')->t('Unknown');
    }

    /**
     * get status color text
     * @return string
     */
    public function getStatusColorText(){
        $status = $this->status;
        $list = self::getStatusOption();

        $color='default';
        if($status==self::STATUS_ACTIVE){
            $color='primary';
        }
        if($status==self::STATUS_INACTIVE){
            $color='danger';
        }

        if (!empty($status) && in_array($status, array_keys($list))) {
            return '<span class="label label-'.$color.'">'.$list[$status].'</span>';
        }
        return '<span class="label label-'.$color.'">'.Yii::$app->getModule('<?= $generator->messageCategory ?>')->t('Unknown').'</span>';
    }

    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
<?php if (empty($generator->databaseName)): ?>
        return '<?= $collectionName ?>';
<?php else: ?>
        return ['<?= $generator->databaseName ?>', '<?= $collectionName ?>'];
<?php endif; ?>
    }
<?php if ($generator->db !== 'mongodb'): ?>

    /**
     * @return \yii\mongodb\Connection the MongoDB connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('<?= $generator->db ?>');
    }
<?php endif; ?>

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
<?php foreach ($attributes as $attribute): ?>
            <?= "'$attribute',\n" ?>
<?php endforeach; ?>
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [<?= "\n            " . implode(",\n            ", $rules) . "\n        " ?>];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
<?php foreach ($labels as $name => $label): ?>
            <?= "'$name' => Yii::\$app->getModule('$generator->messageCategory')->t('$label'),\n" ?>
<?php endforeach; ?>
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            UTCDateTimeBehavior::class,
        ];
    }


    /**
     * @return int timestamp
     */
    public function getUpdatedAt()
    {
        return $this->updated_at->toDateTime()->format('U');
    }

    /**
     * @return int timestamp
     */
    public function getCreatedAt()
    {
        return $this->created_at->toDateTime()->format('U');
    }
}
