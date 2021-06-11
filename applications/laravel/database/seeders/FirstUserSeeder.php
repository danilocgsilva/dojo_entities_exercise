<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Exception;

class FirstUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $userData = [
            'userName' => "Danilo",
            'userMail' => "danilo@test.com",
            'userPassword' => $this->getPassword()
        ];

        if ($this->userExists($userData)) {
            $message = "The user " . $userData["userName"] . " already exists";
        }
        else {
            $this->createUser($userData);
            $message = "The user with name " . $userData["userName"] . " and the mail " . $userData["userMail"] . " was created. Password: " . $userData["userPassword"];
        }

        $this->formattedMessage($message);
    }

    private function userExists(array $userData): bool
    {
        return User::where(User::FILLABLE["email"], $userData["userMail"])->exists();
    }

    private function createUser(array $userData): User
    {
        return User::create([
            User::FILLABLE["name"] => $userData["userName"],
            User::FILLABLE["email"] => $userData["userMail"],
            User::FILLABLE["password"] => bcrypt(
                $userData["userPassword"]
            )
        ]);
    }

    private function formattedMessage(string $message): void
    {
        print($message . "\n");
    }

    private function getPassword(): string
    {
        return "secret1234";
    }
}
