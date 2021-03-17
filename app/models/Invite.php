<?php

namespace app\models;

use app\constants\Messages;
use app\exceptions\InviteCreationException;
use app\exceptions\InviteTokenValidationException;
use CottaCush\Yii2\Date\DateFormat;
use CottaCush\Yii2\Date\DateUtils;
use yii\db\ActiveQuery;
use yii\validators\EmailValidator;

/**
 * Class Invite
 *
 * @property integer $id
 * @property string $email
 * @property string $role_key
 * @property string $status
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_by
 * @property string invite_token
 * @property integer is_active
 *
 * @property Role $role
 */
class Invite extends BaseModel
{
    public static function tableName(): string
    {
        return 'invites';
    }

    public function rules(): array
    {
        return [
            [['email', 'role_key', 'status', 'created_by'], 'required'],
            [['email'], 'email'],
            [['created_at', 'updated_at', 'updated_by'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'email' => 'Email Address',
            'role_key' => 'Role',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getSender(): ActiveQuery
    {
        return $this->hasOne(UserCredential::class, ['id' => 'created_by']);
    }

    /**
     * @return ActiveQuery
     */
    public function getRoleObj(): ActiveQuery
    {
        return $this->hasOne(Role::class, ['key' => 'role_key']);
    }

    /**
     * @return ActiveQuery
     */
    public function getStatusObj(): ActiveQuery
    {
        return $this->hasOne(Status::class, ['key' => 'status']);
    }

    /**
     * @return ActiveQuery
     */
    public function getCreatedBy(): ActiveQuery
    {
        return $this->hasOne(AppUser::class, ['user_auth_id' => 'created_by']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUpdatedBy(): ActiveQuery
    {
        return $this->hasOne(AppUser::class, ['username' => 'updated_by']);
    }

    /**
     * @author Taiwo Ladipo <taiwo.ladipo@cottacush.com>
     * @return bool
     */
    public function isCancelled(): bool
    {
        return $this->status == Status::STATUS_CANCELLED;
    }

    /**
     * @author Taiwo Ladipo <taiwo.ladipo@cottacush.com>
     * @return bool
     */
    public function isPending(): bool
    {
        return $this->status == Status::STATUS_PENDING;
    }

    /**
     * @author Babatunde Otaru <tunde@cottacush.com>
     * @param $email
     * @return string
     */
    public static function getInviteToken($email): string
    {
        return md5($email . date(DateFormat::FORMAT_MYSQL_STYLE));
    }

    /**
     * @return ActiveQuery
     */
    public function getRole(): ActiveQuery
    {
        return $this->hasOne(Role::class, ['key' => 'role_key']);
    }

    /**
     * @param $token
     * @return static
     * @throws InviteTokenValidationException
     * @author Adeyemi Olaoye <yemi@cottacush.com>
     */
    public static function validateToken($token): static
    {
        $invite = Invite::findOne(['invite_token' => $token]);
        if (!$invite) {
            throw new InviteTokenValidationException(Messages::INVITE_NOT_FOUND);
        }

        if ($invite->status == Status::STATUS_CANCELLED) {
            throw new InviteTokenValidationException(Messages::INVITE_ALREADY_CANCELLED);
        }

        if ($invite->status == Status::STATUS_ACCEPTED) {
            throw new InviteTokenValidationException(Messages::INVITE_ALREADY_ACCEPTED);
        }

        return $invite;
    }

    /**
     * @author Taiwo Ladipo <ladipotaiwo01@gmail.com>
     * @param $emails
     * @return bool
     */
    public static function validateEmails($emails): bool
    {
        $validator = new EmailValidator();
        foreach ($emails as $email) {
            if (!$validator->validate($email)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @author Taiwo Ladipo <taiwo.ladipo@cottacush.com>
     * @param array $emails
     * @return bool
     */
    public static function checkDuplicateInviteEmails(array $emails): bool
    {
        return self::find()
            ->where(['IN', 'email', $emails])
            ->andwhere('status != "' . Status::STATUS_CANCELLED . '"')->exists();
    }

    /**
     * @author Taiwo Ladipo <taiwo.ladipo@cottacush.com>
     * @param $emails
     * @param $role
     * @param $createdBy
     * @return bool
     * @throws InviteCreationException
     */
    public static function createInvites($emails, $role, $createdBy): bool
    {
        if (!array_unique($emails)) {
            throw new InviteCreationException(Messages::DUPLICATE_EMAILS_IN_INVITES);
        }

        if (!Invite::validateEmails($emails)) {
            throw new InviteCreationException(Messages::INVALID_EMAIL);
        }

        if (Invite::checkDuplicateInviteEmails($emails)) {
            throw new InviteCreationException(Messages::INVITE_ALREADY_SENT);
        }

        foreach ($emails as $email) {
            $invite = new Invite();
            $invite->email = $email;
            $invite->role_key = $role;
            $invite->created_at = DateUtils::getMysqlNow();
            $invite->created_by = $createdBy;
            $invite->invite_token = Invite::generateInviteToken($email);
            $invite->status = Status::STATUS_PENDING;
            if (!$invite->save()) {
                throw new InviteCreationException($invite->getFirstError());
            }
            //TODO: Add logic for sending email
        }
        return true;
    }

    /**
     * @author Taiwo Ladipo <taiwo.ladipo@cottacush.com>
     * @param $email
     * @return string
     */
    public static function generateInviteToken($email): string
    {
        return md5($email . date(DateFormat::FORMAT_MYSQL_STYLE));
    }

    /**
     * @param null $status
     * @param null $role
     * @param null $createdBy
     * @return ActiveQuery
     *@author Taiwo Ladipo <taiwo.ladipo@cottacush.com>
     */
    public static function getInvites($status = null, $role = null, $createdBy = null): ActiveQuery
    {
        return self::find()
            ->filterWhere([self::tableName() . '.status' => $status])
            ->andFilterWhere([self::tableName() . '.role_key' => $role])
            ->andFilterWhere([self::tableName() . '.created_by' => $createdBy])
            ->joinWith(['roleObj', 'createdBy']);
    }
}