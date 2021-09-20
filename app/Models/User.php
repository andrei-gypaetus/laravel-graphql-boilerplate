<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Mail\TicketCreatedEmail;
use App\Mail\TicketUpdatedEmail;
use App\Mail\TicketStatusChangedEmail;
use App\Mail\TicketUserTaggedEmail;
use App\Mail\TicketUserUntaggedEmail;
use Mail;
use Ramsey\Uuid\Uuid;

class User extends Authenticatable
{
    use Notifiable;
    use HasFactory;
    use HasApiTokens;
    use HasRoles;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function getUserLocale($email)
    {
        $user = User::where("email", "=", $email)->first();

        if ($user) {
            $locale = $user->locale;
        } else {
            $locale = "FR";
        }
        return $locale;
    }

    public static function sendTicketCreatedOrUpdatedEmail($email, $ticketID, $ticketSubject)
    {
        $existingLink = UserTicket::where('ticket_id', '=', $ticketID)->where("user_email", "=", $email)->first();
        $token = sha1(Uuid::uuid4());
        $locale = self::getUserLocale($email);

        if ($existingLink) {
            Mail::to($email)->send(
                new TicketUpdatedEmail($ticketID, config('app.client_dashboard_url') . '/tickets/' . $ticketID . '?token=' . $token, $ticketSubject, $locale)
            );
            $existingLink->token = $token;
            $existingLink->save();
            return;
        }

        Mail::to($email)->send(
            new TicketCreatedEmail($ticketID, config('app.client_dashboard_url') . '/tickets/' . $ticketID . '?token=' . $token, $ticketSubject, $locale)
        );
        UserTicket::create(["user_email" => $email, "ticket_id" => $ticketID, "token" => $token]);
        return;
    }

    public static function sendTicketStatusChangedEmail(string $email, int $ticketID, string $status, string $ticketSubject)
    {
        $existingLink = UserTicket::where('ticket_id', '=', $ticketID)->where("user_email", "=", $email)->first();
        $token = sha1(Uuid::uuid4());
        $locale = self::getUserLocale($email);

        if ($existingLink) {
            Mail::to($email)->send(
                new TicketStatusChangedEmail(
                    $ticketID,
                    config('app.client_dashboard_url') . '/tickets/' . $ticketID . '?token=' . $token,
                    $status,
                    $ticketSubject,
                    $locale
                )
            );
            $existingLink->token = $token;
            $existingLink->save();
        }
    }

    public static function sendTicketTaggedEmail(array $previousTags, array $currentTags, int $ticketID, string $ticketSubject)
    {
        $addedTags = array_diff($currentTags, $previousTags);
        $removedTags = array_diff($previousTags, $currentTags);
        foreach ($addedTags as $tag) {
            if (str_starts_with($tag, '@')) {
                $email = substr($tag, 1);
                $token = sha1(Uuid::uuid4());
                $locale = self::getUserLocale($email);
                Mail::to($email)->send(
                    new TicketUserTaggedEmail($ticketID, config('app.client_dashboard_url') . '/tickets/' . $ticketID . '?token=' . $token, $ticketSubject, $locale)
                );
                UserTicket::create(["user_email" => $email, "ticket_id" => $ticketID, "token" => $token]);
            }
        }

        foreach ($removedTags as $tag) {
            if (str_starts_with($tag, '@')) {
                $email = substr($tag, 1);
                $locale = self::getUserLocale($email);
                $existingLink = UserTicket::where('ticket_id', '=', $ticketID)->where("user_email", "=", $email)->first();
                $token = sha1(Uuid::uuid4());
                Mail::to($email)->send(
                    new TicketUserUntaggedEmail($ticketID, config('app.client_dashboard_url') . '/tickets/' . $ticketID . '?token=' . $token, $ticketSubject, $locale)
                );
                $existingLink->token = $token;
                $existingLink->save();
            }
        }
    }
}
