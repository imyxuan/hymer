<?php

namespace IMyxuan\Hymer\Tests;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use IMyxuan\Hymer\Models\Role;
use IMyxuan\Hymer\Models\User;

class UserProfileTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected $editPageForTheCurrentUser;

    protected $listOfUsers;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = Auth::loginUsingId(1);

        $this->editPageForTheCurrentUser = route('hymer.users.edit', [$this->user->id]);

        $this->listOfUsers = route('hymer.users.index');
    }

    public function testCanSeeTheUserInfoOnHisProfilePage()
    {
        $this->visit(route('hymer.profile'))
             ->seeInElement('h4', $this->user->name)
             ->seeInElement('.user-email', $this->user->email)
             ->seeLink(__('hymer::profile.edit'));
    }

    public function testCanEditUserName()
    {
        $this->visit(route('hymer.profile'))
             ->click(__('hymer::profile.edit'))
             ->see(__('hymer::profile.edit_user'))
             ->seePageIs($this->editPageForTheCurrentUser)
             ->type('New Awesome Name', 'name')
             ->press(__('hymer::generic.save'))
             ->seePageIs($this->listOfUsers)
             ->seeInDatabase(
                 'users',
                 ['name' => 'New Awesome Name']
             );
    }

    public function testCanEditUserEmail()
    {
        $this->visit(route('hymer.profile'))
             ->click(__('hymer::profile.edit'))
             ->see(__('hymer::profile.edit_user'))
             ->seePageIs($this->editPageForTheCurrentUser)
             ->type('another@email.com', 'email')
             ->press(__('hymer::generic.save'))
             ->seePageIs($this->listOfUsers)
             ->seeInDatabase(
                 'users',
                 ['email' => 'another@email.com']
             );
    }

    public function testCanEditUserPassword()
    {
        $this->visit(route('hymer.profile'))
             ->click(__('hymer::profile.edit'))
             ->see(__('hymer::profile.edit_user'))
             ->seePageIs($this->editPageForTheCurrentUser)
             ->type('hymer-rocks', 'password')
             ->press(__('hymer::generic.save'))
             ->seePageIs($this->listOfUsers);

        $updatedPassword = DB::table('users')->where('id', 1)->first()->password;
        $this->assertTrue(Hash::check('hymer-rocks', $updatedPassword));
    }

    public function testCanEditUserAvatar()
    {
        $this->visit(route('hymer.profile'))
             ->click(__('hymer::profile.edit'))
             ->see(__('hymer::profile.edit_user'))
             ->seePageIs($this->editPageForTheCurrentUser)
             ->attach($this->newImagePath(), 'avatar')
             ->press(__('hymer::generic.save'))
             ->seePageIs($this->listOfUsers)
             ->dontSeeInDatabase(
                 'users',
                 ['id' => 1, 'avatar' => 'user/default.png']
             );
    }

    public function testCanEditUserEmailWithEditorPermissions()
    {
        $user = \IMyxuan\Hymer\Models\User::factory()->for(\IMyxuan\Hymer\Models\Role::factory())->create();
        $editPageForTheCurrentUser = route('hymer.users.edit', [$user->id]);
        // add permissions which reflect a possible editor role
        // without permissions to edit  users
        $user->role->permissions()->attach(\IMyxuan\Hymer\Models\Permission::whereIn('key', [
            'browse_admin',
            'browse_users',
        ])->get()->pluck('id')->all());
        Auth::onceUsingId($user->id);
        $this->visit(route('hymer.profile'))
             ->click(__('hymer::profile.edit'))
             ->see(__('hymer::profile.edit_user'))
             ->seePageIs($editPageForTheCurrentUser)
             ->type('another@email.com', 'email')
             ->press(__('hymer::generic.save'))
             ->seePageIs($this->listOfUsers)
             ->seeInDatabase(
                 'users',
                 ['email' => 'another@email.com']
             );
    }

    public function testCanSetUserLocale()
    {
        $this->visit(route('hymer.profile'))
             ->click(__('hymer::profile.edit'))
             ->see(__('hymer::profile.edit_user'))
             ->seePageIs($this->editPageForTheCurrentUser)
             ->select('de', 'locale')
             ->press(__('hymer::generic.save'));

        $user = User::find(1);
        $this->assertTrue(($user->locale == 'de'));

        // Validate that app()->setLocale() is called
        Auth::loginUsingId($user->id);
        $this->visitRoute('hymer.dashboard');
        $this->assertTrue(($user->locale == $this->app->getLocale()));
    }

    public function testRedirectBackAfterEditWithoutBrowsePermission()
    {
        $user = User::find(1);

        // Remove `browse_users` permission
        $user->role->permissions()->detach(
            $user->role->permissions()->where('key', 'browse_users')->first()
        );

        $this->visit($this->editPageForTheCurrentUser)
             ->press(__('hymer::generic.save'))
             ->seePageIs($this->editPageForTheCurrentUser);
    }

    protected function newImagePath()
    {
        return realpath(__DIR__.'/temp/new_avatar.png');
    }
}
