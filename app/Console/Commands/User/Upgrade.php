<?php

namespace App\Console\Commands\User;

use App\Enums\Action;
use App\Enums\FieldName;
use App\Events\Log\User\LogPermissionActionEvent;
use App\Services\Action\ActionServiceInterface;
use App\Services\User\UserService;
use Illuminate\Console\Command;
use Spatie\FlareClient\Http\Exceptions\NotFound;

class Upgrade extends Command
{
    protected $signature = 'user:upgrade {actor : Your email} {email : The email of the user you want to promote}';

    protected $description = 'Upgrade User to Admin';

    public function handle(UserService $service, ActionServiceInterface $actionService): void
    {
        try {
            $actor = $service->getUser($this->argument("actor"), FieldName::Email);
            if (!$actor->isAdmin()) {
                $this->info("Cant upgrade User - you don't have permissions");
                return;
            }
            $user = $service->getUser($this->argument("email"), FieldName::Email);
            $actorId = $actor->id;
            $oldUser = $user->toJson();
            $service->upgrade($user->id);
            $user = $service->getUser($user->id);
            $this->info("Success");
            LogPermissionActionEvent::dispatch($actorId, $oldUser, $user->toJson(), Action::Upgrade);
        } catch (NotFound) {
            $this->info("Error - some user not found by email");
        }
    }
}
