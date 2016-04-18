<?php namespace app\models;

use yii\behaviors\TimestampBehavior;
use app\models\User;
use app\components\notification\PlaceholdersInterface;
use yii\helpers\Url;
use Yii;

/**
 * This is the model class for table "article".
 *
 * @property integer $id
 * @property integer $author_id
 * @property string $title
 * @property string $text
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $author
 */
class Article extends \yii\db\ActiveRecord implements PlaceholdersInterface
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['author_id', 'title'], 'required'],
            [['author_id'], 'integer'],
            [['text'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['author_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'author_id' => 'Author ID',
            'title' => 'Title',
            'text' => 'Text',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'author_id']);
    }
    
    public function getPlaceholders()
    {
        return [
            '{title}' => $this->title,
            '{url}' => Url::toRoute(['/article/view', 'id' => $this->id], true)
        ];
    }
}
