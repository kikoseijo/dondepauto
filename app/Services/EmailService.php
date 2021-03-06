<?php

/**
 * Created by PhpStorm.
 * User: Desarrollador 1
 * Date: 25/04/2016
 * Time: 5:14 PM
 */

namespace App\Services;

use App\Entities\Platform\Space\Space;
use App\Entities\Platform\User;
use App\Entities\Proposal\Proposal;
use App\Entities\Views\Advertiser;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Mail;

class EmailService
{
    protected $bccAdmin = ['email' => 'andres@dondepauto.co', 'name' => 'Andrés Pinzón'];
    protected $advertiserEmail = ['email' => 'leonardo@dondepauto.co', 'name' => 'Leonardo Rueda'];
    protected $publisherEmail = ['email' => 'alexander@dondepauto.co', 'name' => 'Alexander Niño'];


    /**
     * @param $toEmail
     * @param $toName
     * @param $template
     * @param array $parameters
     * @param $subject
     * @param array $bcc
     * @param string $fromEmail
     * @param string $fromName
     */
    protected function send($toEmail, $toName, $template, array $parameters, $subject, array $bcc = [], $fromEmail = "noresponder@dondepauto.co", $fromName = "Notificaciones DóndePauto")
    {
        if(env('APP_ENV') == 'production') {
            Mail::send($template, $parameters, function ($m) use ($fromEmail, $fromName, $toEmail, $toName, $subject, $bcc) {
                $m->from($fromEmail, $fromName)
                    ->to($toEmail, $toName)
                    ->bcc($this->bccAdmin['email'], $this->bccAdmin['name'])
                    ->subject($subject);

                foreach ($bcc as $b) {
                    $m->bcc($b['email'], $b['name']);
                }
            });
        }
        else {
            Mail::send($template, $parameters, function ($m) use ($fromEmail, $fromName, $toEmail, $toName, $subject, $bcc) {
                $m->from($fromEmail, $fromName)
                    ->to($this->bccAdmin['email'], $this->bccAdmin['name'])
                    ->subject($subject);
            });
        }
    }


    /**
     * @param array $to
     * @param $template
     * @param array $parameters
     * @param $subject
     * @param array $bcc
     * @param string $fromEmail
     * @param string $fromName
     */
    protected function sendDefault(array $to, $template, array $parameters, $subject, array $bcc = [], $fromEmail = "noresponder@dondepauto.co", $fromName = "Notificaciones DóndePauto")
    {
        $this->send($to['email'], $to['name'], $template, $parameters, $subject, $bcc, $fromEmail, $fromName);
    }


    /**
     * @param $user
     * @param $template
     * @param array $parameters
     * @param $subject
     * @param array $bcc
     * @param string $fromEmail
     * @param string $fromName
     */
    protected function sendUser($user, $template, array $parameters, $subject, array $bcc = [], $fromEmail = "noresponder@dondepauto.co", $fromName = "Notificaciones DóndePauto")
    {
        $this->send($user->email, ucfirst($user->first_name), $template, $parameters, $subject, $bcc, $fromEmail, $fromName);
    }


    /**
     * @param $advertiser
     * @param $template
     * @param array $parameters
     * @param $subject
     * @param array $bcc
     */
    protected function sendAdvertiser($advertiser, $template, array $parameters, $subject, array $bcc = [])
    {
        $this->sendUser($advertiser, $template, $parameters, $subject, $bcc, $this->advertiserEmail['email'], $this->advertiserEmail['name']);
    }


    /**
     * @param $publisher
     * @param $template
     * @param array $parameters
     * @param $subject
     * @param array $bcc
     */
    protected function sendPublisher($publisher, $template, array $parameters, $subject, array $bcc = [])
    {
        $this->sendUser($publisher, $template, $parameters, $subject, $bcc, $this->publisherEmail['email'], $this->publisherEmail['name']);
    }

    /**
     * @param User $user
     * @param $view
     * @param $code
     * @param string $type
     */
    protected function sendInvitation(User $user, $view, $code, $type = 'advertiser')
    {
        $subject = ucfirst($user->company) .  ', bienvenido a la agencia DóndePauto';
        $parameters = ['user' => $user, 'code' => $code];

        if($type == 'advertiser') {
            $this->sendAdvertiser($user, 'emails.' . $view, $parameters, $subject);
        }
        else {
            $this->sendPublisher($user, 'emails.' . $view, $parameters, $subject);
        }
    }

    /**
     * @param User $publisher
     * @param $code
     */
    public function sendPublisherInvitation(User $publisher, $code)
    {
        $this->sendInvitation($publisher, 'publisher.confirm', $code, 'publisher');
    }

    /**
     * @param User $advertiser
     * @param $code
     */
    public function sendAdvertiserInvitation(User $advertiser, $code)
    {
        $this->sendInvitation($advertiser, 'advertiser.confirm', $code, 'advertiser');
    }

    /**
     * @param User $publisher
     * @param $letterPath
     */
    public function sendLetter(User $publisher, $letterPath)
    {
        Mail::send('emails.publisher.letter', ['publisher' => $publisher], function ($m) use ($publisher, $letterPath) {
            $m->from($this->publisherEmail['email'], $this->publisherEmail['name'])
                ->to($publisher->representative->email, $publisher->representative->name)
                ->cc($publisher->email, $publisher->name)
                ->bcc($this->bccAdmin['email'], $this->bccAdmin['name'])
                ->bcc($this->publisherEmail['email'], $this->publisherEmail['name'])
                ->subject('Carta de Incentivos DóndePauto')
                ->attach($letterPath, []);
        });
    }

    /**
     * @param User $publisher
     * @param $comments
     */
    public function changeAgreement(User $publisher, $comments)
    {
        $this->sendDefault($this->publisherEmail, 'emails.publisher.change-agreement', [
            'publisher' => $publisher,
            'comments' => $comments
        ], $publisher->first_name . ' de ' . $publisher->company . ' solicita cambiar datos de acuerdo');
    }

    /**
     * @param User $user
     * @param string $newType
     * @param array $to
     * @param array $bcc
     */
    protected function notifyChangeRole(User $user, $newType = 'Anunciante', array $to, array $bcc = [])
    {
        $this->sendDefault($to, 'emails.notifications.change-user-role', [
            'user'      => $user,
            'newType'   => $newType,
            'timestamp' => \Carbon\Carbon::now()->toDateTimeString()
        ], $user->first_name . ' de ' . $user->company . ' ahora es ' . $newType, $bcc);
    }

    /**
     * @param User $advertiser
     */
    public function notifyChangeAdvertiserRole(User $advertiser)
    {
        $this->notifyChangeRole($advertiser, 'Anunciante', $this->advertiserEmail, [$this->publisherEmail]);
    }

    /**
     * @param User $publisher
     */
    public function notifyChangePublisherRole(User $publisher)
    {
        $this->notifyChangeRole($publisher, 'Medio Publicitario', $this->publisherEmail);
    }

    /**
     * @param User $publisher
     * @param Space $space
     */
    public function notifyNewOffer(User $publisher, Space $space)
    {
        $this->sendDefault($this->advertiserEmail, 'emails.notifications.new-offer', [
            'publisher' => $publisher,
            'space' => $space
        ], $publisher->company . " acaba de crear una nueva oferta", [$this->publisherEmail]);
    }


    /**
     * @param Space $space
     */
    public function notifyEditOffer(Space $space)
    {
        $this->sendDefault($this->advertiserEmail, 'emails.notifications.edit-offer', [
            'publisher' => $space->publisher,
            'space'     => $space
        ], $space->publisher->company . " acaba de editar una oferta", [$this->publisherEmail]);
    }

    /**
     * @param Space $space
     * @param $option
     */
    public function notifyInactiveOffer(Space $space, $option)
    {
        $this->sendPublisher($space->publisher, 'emails.notifications.inactive-'. $option . '-offer', [
            'publisher' => $space->publisher,
            'space'     => $space
        ], "Hemos inactivado una oferta tuya");
    }

    /**
     * @param User $publisher
     */
    public function notifyDocuments(User $publisher)
    {
        $this->sendDefault($this->publisherEmail, 'emails.notifications.new-documents', [
            'publisher' => $publisher
        ], $publisher->company . " acaba de enviar los documentos societarios", [$this->publisherEmail]);
    }

    /**
     * @param User $user
     */
    public function notifyUserDelete(User $user)
    {
        $this->sendUser($user, 'emails.notifications.delete-user', [
            'user' => $user
        ], $user->first_name . ", hemos dado de baja tu cuenta en DóndePauto");
    }

    /**
     * @param Space $space
     * @param Collection $advertisers
     * @param int $discount
     * @return bool
     */
    public function suggest(Space $space, Collection $advertisers, $discount = 0)
    {
        foreach($advertisers as $advertiser) {
            $this->sendAdvertiser($advertiser, 'emails.advertiser.suggest', [
                'space' => $space,
                'advertiser' => $advertiser,
                'discount' => $discount
            ], ucfirst($advertiser->first_name) . ", recomendamos este medio publicitario para tu empresa");
        }

        return true;
    }

    /**
     * @param Proposal $proposal
     * @param Collection $spaces
     */
    public function notifyProposalSelected(Proposal $proposal, Collection $spaces)
    {
        $this->sendDefault($this->advertiserEmail, 'emails.notifications.proposal-selected', [
            'proposal'      => $proposal,
            'advertiser'    => $proposal->getAdvertiser(),
            'spaces'        => $spaces
        ], $proposal->getAdvertiser()->company . ", ha seleccionado " . $spaces->count() . " espacios de la propuesta");
    }

    /**
     * @param Proposal $proposal
     * @param User $advertiser
     */
    public function sendProposal(Proposal $proposal, User $advertiser)
    {
        $this->sendAdvertiser($advertiser, 'emails.proposal.send', [
            'proposal'      => $proposal,
            'advertiser'    => $advertiser
        ], "Hola " . $advertiser->first_name . ", aquí está la propuesta que solicitaste", [$this->advertiserEmail]);
    }

    /**
     * @param Proposal $proposal
     * @param User $advertiser
     */
    public function sendProposalSettings(Proposal $proposal, User $advertiser)
    {
        $this->sendAdvertiser($advertiser, 'emails.proposal.send-settings', [
            'proposal'      => $proposal,
            'advertiser'    => $advertiser
        ], "Hola " . $advertiser->first_name . ", hemos mejorado la propuesta para su campaña de medios", [$this->advertiserEmail]);
    }
}