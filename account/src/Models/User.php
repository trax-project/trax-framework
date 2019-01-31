<?php

namespace Trax\Account\Models;

use Illuminate\Notifications\Notifiable;
use Auth;

use Trax\Account\Notifications\ResetPassword as ResetPasswordNotification;
use Trax\Account\Notifications\PasswordReset as PasswordResetNotification;
use Trax\Account\Notifications\PasswordDefined as PasswordDefinedNotification;
use Trax\Account\Notifications\Invitation as InvitationNotification;

traxCreateAuthenticatableSwitchClass('Trax\Account\Models', 'trax-account', 'User');

class User extends UserAuthenticatable
{
    use Notifiable;

    /**
     * The table associated with the model.
     */
    protected $table = 'trax_account_users';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'uuid', 'username', 'email', 'password', 'admin', 'active', 'source_code', 
        'role_id', 'entity_type_code', 'organization_id', 'entity_id', 'data', 
    ];

    /**
     * The attributes that should be visible.
     */
    protected $visible = [
        'id', 'uuid', 'username', 'email', 'admin', 'active', 'source_code', 
        'role_id', 'entity_type_code', 'organization_id', 'entity_id', 
        'role', 'organization', 'entity',
        'data', 'created_at', 'updated_at'
    ];


    /**
     * Send the password reset notification.
     */
    public function sendPasswordResetNotification($token)
    {
        if (Auth::check()) $this->notify(new InvitationNotification($token, $this->lang));
        else $this->notify(new ResetPasswordNotification($token, $this->lang));
    }

    /**
     * Send the password reset alert.
     */
    public function sendPasswordResetAlert()
    {
        $this->notify(new PasswordResetNotification($this->lang));
    }

    /**
     * Send the new password notification.
     */
    public function sendPasswordDefined()
    {
        $this->notify(new PasswordDefinedNotification($this->lang));
    }

    /**
     * Set picture.
     */
    public function setPicture($url)
    {
        $data = $this->jsonData();;
        $data->picture = $url;
        $this->data = $data;
        $this->save();
    }

    /**
     * Set preferences.
     */
    public function setPreferences($preferences)
    {
        $data = $this->jsonData();
        if (!isset($data->preferences)) $data->preferences = (object)[];
        foreach ($preferences as $prop => $val) {
            $data->preferences->$prop = $val;
        }
        $this->data = $data;
        $this->save();
    }

    /**
     * Set status.
     */
    public function setStatus($name)
    {
        $data = $this->jsonData();
        if (!isset($data->status)) $data->status = (object)[];
        $data->status->$name = traxNowString();
        $this->data = $data;
        $this->save();
    }

    /**
     * Get status.
     */
    public function getStatus($name)
    {
        $data = $this->jsonData();
        if (!isset($data->status) || !isset($data->status->$name)) return false;
        return $data->status->$name;
    }

    /**
     * Get the user lang.
     */
    public function getLangAttribute($value)
    {
        $data = $this->jsonData();
        return isset($data->lang) ? $data->lang : config('app.locale');
    }

    /**
     * Get the user fullname.
     */
    public function getFullnameAttribute($value)
    {
        $data = $this->jsonData();
        return $data->lastname . ' ' . $data->firstname;
    }

    /**
     * Get JSON Data.
     */
    public function jsonData()
    {
        if (!is_array($this->data)) return $this->data;
        return json_decode(json_encode($this->data));
    }

    /**
     * Get the global role.
     */
    public function role()
    {
        return $this->belongsTo('Trax\Account\Models\Role');
    }

    /**
     * Get the organization.
     */
    public function organization()
    {
        return $this->belongsTo('Trax\Account\Models\Entity', 'organization_id');
    }

    /**
     * Get the entity.
     */
    public function entity()
    {
        return $this->belongsTo('Trax\Account\Models\Entity');
    }

    /**
     * The registered groups.
     */
    public function groups()
    {
        return $this->belongsToMany('Trax\Account\Models\Group', 'trax_account_group_user');
    }

    /**
     * The signed agreements.
     */
    public function agreements()
    {
        return $this->belongsToMany('Trax\Account\Models\Agreement', 'trax_account_agreement_user');
    }

    /**
     * Learning unit states.
     */
    public function learningUnitStates()
    {
        if (!config('trax-dashboard.enabled')) return $this;
        return $this->hasMany('Trax\Dashboard\Models\LearningUnitUserState');
    }

    /**
     * Collaborators.
     */
    public function collaborators()
    {
        $data = $this->jsonData();
        if (isset($this->entity_id) && $this->entity_id
            && isset($data->rights_level_code) && $data->rights_level_code == 'entity') {
    
            // Entity level
            $entityIds = [$this->entity_id];

        } else if (isset($this->organization_id) && $this->organization_id) {

            // Organization level
            $entityIds = $this->entityIds();

        } else {
            return false;
        }
        return $this->whereIn('organization_id', $entityIds)->orWhereIn('entity_id', $entityIds);
    }

    /**
     * Collaborator IDs.
     */
    public function collaboratorIds()
    {
        $collaborators = $this->collaborators();
        if (!$collaborators) return [];
        return $collaborators->pluck('id')->unique()->toArray();
    }

    /**
     * Collaborator groups.
     */
    public function collaboratorGroupIds()
    {
        $groupIds = [];
        $collaborators = $this->collaborators();
        if (!$collaborators) return [];
        $collaborators = $collaborators->get();
        foreach ($collaborators as $collaborator) {
            $ids = $collaborator->groups()->pluck('id')->toArray();
            $groupIds = array_merge($groupIds, $ids);
        }
        return array_unique($groupIds);
    }

    /**
     * Entities IDs.
     */
    public function entityIds($globalLevelAllowed = false, $onlyChildren = false)
    {
        $data = $this->jsonData();

        // Entity level
        if (isset($this->entity_id) && $this->entity_id
            && isset($data->rights_level_code) && $data->rights_level_code == 'entity') {

            return $onlyChildren ? [$this->entity_id] : [$this->organization_id, $this->entity_id];
        }

        // Organization level
        if (isset($this->organization_id) && $this->organization_id && ((isset($data->rights_level_code) && $data->rights_level_code == 'organization')
            || !$globalLevelAllowed)) {

            $ids = $this->organization->children()->pluck('id')->toArray();
            if (!$onlyChildren) $ids[] = $this->organization_id;
            return $ids;

        }

        // Global level
        return $this->organization->cousins($onlyChildren)->pluck('id')->toArray();
    }

    /**
     * Entities UUIDs.
     */
    public function entityUuids($globalLevelAllowed = false, $onlyChildren = false)
    {
        $data = $this->jsonData();

        // Entity level
        if (isset($this->entity_id) && $this->entity_id
            && isset($data->rights_level_code) && $data->rights_level_code == 'entity') {

            return $onlyChildren ? [$this->entity->uuid] : [$this->organization->uuid, $this->entity->uuid];
        }

        // Organization level
        if (isset($this->organization_id) && $this->organization_id && ((isset($data->rights_level_code) && $data->rights_level_code == 'organization')
            || !$globalLevelAllowed)) {

            $ids = $this->organization->children()->pluck('uuid')->toArray();
            if (!$onlyChildren) $ids[] = $this->organization->uuid;
            return $ids;

        }

        // Global level
        return $this->organization->cousins($onlyChildren)->pluck('uuid')->toArray();
    }

    /**
     * Spatie Media Library: Register media collections.
     */
    public function registerMediaCollections()
    {
        $this->addMediaCollection('avatars')->useDisk('avatars')->singleFile();
    }


}
