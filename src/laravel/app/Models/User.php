<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use JsonException;
use Laravel\Sanctum\HasApiTokens;
use denis660\Centrifugo\Centrifugo;
use GuzzleHttp\Client as HttpClient;
use App\Services\MessagePublisher;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, softDeletes, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * @return BelongsToMany
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'permission_user', 'user_id', 'permission_id');
    }

    /**
     * @param string $permission
     * @return bool
     */
    public function hasPermission(string $permission): bool
    {
        return (bool) $this->permissions()->where('name', $permission)->exists();
    }

    /**
     * @return array
     */
    public function permissionsToArray(): array
    {
        $permissions = [];
        foreach($this->permissions()->get() as $permission)
        {
            $permissions[] = $permission->name;
        }
        return $permissions;
    }

    /**
     * @return string
     */
    public function getCentrifugoPublicToken(int $time = 604800, array $channels = []): string
    {
        return MessagePublisher::getInstance()
            ->getMessenger()
            ->generateConnectionToken((string)$this->id, $time, [
            'name' => $this->email,
        ]);
    }

    /**
     * @param string $channel
     * @param int $time
     * @return string
     */
    public function getCentrifugoPrivateToken(string $channel, int $time = 36000): string
    {
        return MessagePublisher::getInstance()
            ->getMessenger()
            ->generatePrivateChannelToken((string)$this->id, $channel, $time, [
                'name' => $this->email,
            ]);
    }
}
