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
            'userPassword' => "secret123"
        ];

        if ($this->userExists($userData)) {
            print("The user " . $userData["userName"] . " already exists");
        }
        else {
            $this->createUser($userData);
            print("The user with name " . $userData["name"] . " and the mail " . $userData["userMail"] . " was created.");
        }
    }

    private function userExists(array $userData): bool
    {
        return User::where(User::FILLABLE["email"], $userData["userMail"])->exists();
    }

    private function createUser(array $userData): User
    {
            User::create([
                User::FILLABLE["name"] => $userData["userName"],
                User::FILLABLE["email"] => $userData["userMail"],
                User::FILLABLE["password"] => $userData["password"]
            ]);
        }
    }
}
