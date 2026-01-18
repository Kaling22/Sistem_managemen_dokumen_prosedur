<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    // ===============================
    // ROLE CONSTANT
    // ===============================
    const ROLE_ADMIN = 0;
    const ROLE_DOCO = 1;
    const ROLE_PJO = 2;
    const ROLE_DEPT_HEAD = 3;
    const ROLE_SECTION_HEAD = 4;
    const ROLE_GROUP_LEADER = 5;
    const ROLE_NON_STAF = 6;

    protected $fillable = [
        'nama',
        'nrp',
        'kontak',
        'password',
        'role',
        'departemen',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    

    public function getRoleNameAttributeProfile()
    {
        switch ($this->role) {
            case 0:
                return 'Admin';
            case 1:
                return 'DOCO';
            case 2:
                return 'PJO';
            case 3:
                return 'Department Head';
            case 4:
                return 'Section Head';
            case 5:
                return 'Group Leader';
            case 6:
                return 'Non Staf';
            default:
                return 'Tidak diketahui';
        }
    }

    // ===============================
    // ACCESSOR ROLE NAME (PHP 7.4 SAFE)
    // ===============================
    public function getRoleNameAttribute()
    {
        switch ($this->role) {
            case self::ROLE_ADMIN:
                return 'Admin';
            case self::ROLE_DOCO:
                return 'DOCO';
            case self::ROLE_PJO:
                return 'PJO';
            case self::ROLE_DEPT_HEAD:
                return 'Department Head';
            case self::ROLE_SECTION_HEAD:
                return 'Section Head';
            case self::ROLE_GROUP_LEADER:
                return 'Group Leader';
            case self::ROLE_NON_STAF:
                return 'Non Staf';
            default:
                return 'Unknown';
        }
    }

    // ===============================
    // ROLE HELPER
    // ===============================
    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isDoco()
    {
        return $this->role === self::ROLE_DOCO;
    }
    
    public function isPjo()
    {
        return $this->role === self::ROLE_PJO;
    }
    
    public function isDeptHead()
    {
        return $this->role === self::ROLE_DEPT_HEAD;
    }
    
    public function isSectionHead()
    {
        return $this->role === self::ROLE_SECTION_HEAD;
    }

    public function isGroupLeader()
    {
        return $this->role === self::ROLE_GROUP_LEADER;
    }
    
    public function isNonStaf()
    {
        return $this->role === self::ROLE_NON_STAF;
    }
}
