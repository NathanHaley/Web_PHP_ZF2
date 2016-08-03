<?php
namespace Account\Model\Entity;

// use Doctrine\Common\Collections\ArrayCollection;
use Zend\Form\Annotation;
use Account\Model\PasswordAwareInterface;
use Zend\Crypt\Password\PasswordInterface;

/**
 * @Annotation\Name("user")
 * @Annotation\Hydrator({"type": "Zend\Stdlib\Hydrator\ClassMethods", "options": {"underscoreSeparatedKeys": false}})
 *
 * @Entity @Table(name="a_user")
 */
class User implements PasswordAwareInterface
{
    use \Util\Model\Entity\Traits\EntityBase;

    /**
     * @Annotation\Exclude()
     *
     * @Id @GeneratedValue @Column(type="integer")
     */
    protected $id;

    /**
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Options({"label":"Display Name:*"})
     * @Annotation\Attributes({"required": true,"placeholder":"Required: Display name..."})
     * @Annotation\Validator({"name":"StringLength","options":{"min":1,"max":40}})
     * @Annotation\Flags({"priority": "900"})
     *
     * @Column(name="display_name", type="string")
     */
    protected $displayName;

    /**
     * @Annotation\Type("Zend\Form\Element\Password")
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"StringLength","options":{"min":4,"max":100}})
     * @Annotation\Options({"label":"Password:*", "priority": "400"})
     * @Annotation\Attributes({"required": true,"placeholder":"Required: Password..."})
     * @Annotation\Flags({"priority": "300"})
     *
     * @Column(type="string")
     */
    protected $password;

    /**
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\AllowEmpty({"true"})
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Options({"label":"First Name:"})
     * @Annotation\Attributes({"placeholder":"First name..."})
     * @Annotation\Validator({"name":"StringLength","options":{"min":1,"max":100}})
     * @Annotation\Flags({"priority": "500"})
     *
     * @Column(name="first_name", type="string")
     */
    protected $firstName;

    /**
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\AllowEmpty({"true"})
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Options({"label":"Middle Name:"})
     * @Annotation\Attributes({"placeholder":"Middle name..."})
     * @Annotation\Validator({"name":"StringLength","options":{"min":1,"max":100}})
     * @Annotation\Flags({"priority": "500"})
     *
     * @Column(name="middle_name", type="string")
     */
    protected $middleName;

    /**
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\AllowEmpty({"true"})
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Options({"label":"Last Name:"})
     * @Annotation\Attributes({"placeholder":"Last name..."})
     * @Annotation\Validator({"name":"StringLength","options":{"min":1,"max":100}})
     * @Annotation\Flags({"priority": "500"})
     *
     * @Column(name="last_name", type="string")
     */
    protected $lastName;

    /**
     * @Annotation\Exclude()
     *
     * @Column(type="string")
     */
    protected $image;

    /**
     * @Annotation\Exclude()
     *
     * @Column(name="a_role_id", type="integer")
     */
    protected $aRoleId;

    /**
     * @Annotation\Type("Zend\Form\Element\Select")
     * @Annotation\Required({"required":"true"})
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Options({"label":"Role:",
     * "value_options":{"member":"Member","admin":"Administrator"}})
     * @Annotation\Validator({"name":"InArray","options":{"haystack":{"member","admin"},
     * "messages":{"notInArray":"Role does not exist"}}})
     * @Annotation\Attributes({"value":"member"})
     * @Annotation\Flags({"priority": "400"})
     *
     * @Column(name="role", type="string")
     */
    protected $role;

    /**
     * @Annotation\Exclude()
     *
     * @Column(name="fb_user_id", type="integer")
     */
    protected $fbUserId;

    /**
     * @Annotation\Exclude()
     *
     * @Column(name="fb_access_token", type="string")
     */
    protected $fbAccessToken;

    /**
     * @Annotation\Exclude()
     *
     * @Column(name="fb_access_token_expire_ts", type="datetime")
     */
    protected $fbAccessTokenExpireTs;

    /**
     * @Annotation\Exclude()
     *
     * @Column(name="fb_refresh_token", type="string")
     */
    protected $fbRefreshToken;

    /**
     * @Annotation\Exclude()
     *
     * @Column(name="fb_refresh_token_expire_ts", type="datetime")
     */
    protected $fbRefreshTokenExpireTs;

    /**
     * @Annotation\Exclude()
     *
     * @var PasswordInterface
     */
    protected $passwordAdapter;

    /**
     * @Annotation\Exclude()
     * @ComposedObject({"target_object":"Account\Model\Entity\Email",
     * "is_collection":"true",
     * "options":{"count":3, "should_create_template":true, "allow_add" : true}})
     *
     * @OneToMany(targetEntity="Email", mappedBy="user")
     */
    //protected $emails;

    /**
     * @Annotation\Type("Zend\Form\Element\Email")
     * @Annotation\Validator({"name":"EmailAddress"})
     * @Annotation\Options({"label":"Email/Username:*<br /><small class='text-muted'>(Not visible to members)</small>"})
     * @Annotation\Validator({"name":"StringLength","options":{"min":6,"max":255}})
     * @Annotation\Attributes({"type":"email","required": true,"placeholder": "Required: Email Address..."})
     * @Annotation\Flags({"priority": "600"})
     *
     * @Column(type="string")
     */
    protected $email;

    /**
     * @Annotation\Exclude()
     *
     * @OneToMany(targetEntity="Phone", mappedBy="user")
     */
    protected $phones;

    /**
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\AllowEmpty({"true"})
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Options({"label":"Your Phone Number:"})
     * @Annotation\Validator({"name":"StringLength","options":{"min":5,"max":255}})
     * @Annotation\Validator({"name":"RegEx",
     * "options": {
     * "pattern": "/^[\d-\/]+$/",
     * "messages":
     * {"regexNotMatch":"Only numbers, 0-9, and dashes '-'"}
     * }
     * })
     * @Annotation\Attributes({"placeholder": "555-555-5555...","type":"tel","pattern":"^[\d-/]+$","messages":{"regexNotMatch":"Only numbers, 0-9, and dashes '-'"}})
     * @Annotation\Flags({"priority": "100"})
     *
     * @Column(type="string")
     */
    protected $phone;

    /**
     * @Annotation\Exclude()
     *
     * @OneToMany(targetEntity="Address", mappedBy="user")
     */
    //protected $addresses;

    public function __construct()
    {
        // $this->emails = new ArrayCollection();
        // $this->phones = new ArrayCollection();
        // $this->addresses = new ArrayCollection();
    }

    /**
     *
     * @return the $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @param field_type $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    public function getDisplayName()
    {
        return $this->displayName;
    }

    public function setDisplayName($displayName)
    { // @todo check for uniqueness
        $this->displayName = $displayName;
    }

    /**
     * Gets the current password hash
     *
     * @return the $password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Sets the password
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $this->hashPassword($password);
    }

    /**
     * Verifies if the provided password matches the stored one.
     *
     * @param string $password
     *            clear text password
     * @return boolean
     */
    public function verifyPassword($password)
    {
        return $this->passwordAdapter->verify($password, $this->password);
    }

    /**
     * Verifies if the provided Facebook token matches the stored one.
     *
     * @param string $token
     *            in clear text
     * @return boolean
     */
    public function verifyFaceBookAccessToken($token)
    {
        return $token === $this->fbAccessToken;
    }

    /**
     * Hashes a password
     *
     * @param string $password
     * @return string
     */
    private function hashPassword($password)
    {
        return $this->passwordAdapter->create($password);
    }

    /**
     * Sets the password adapter
     *
     * @param PasswordInterface $adapter
     */
    public function setPasswordAdapter(PasswordInterface $adapter)
    {
        $this->passwordAdapter = $adapter;
    }

    /**
     * Gets the password adapter
     *
     * @return PasswordInterface
     */
    public function getPasswordAdapter()
    {
        return $this->passwordAdapter;
    }
    /*
    public function getEmails()
    {
        return $this->emails;
    }

    public function getPhones()
    {
        return $this->phones;
    }

    public function getAddresses()
    {
        return $this->addresses;
    }

    public function addEmails($email)
    {
        $this->emails[] = $email;
    }

    public function addPhone($phone)
    {
        $this->phones[] = $phone;
    }

    public function addAddress($address)
    {
        $this->addresses[] = $address;
    }
    */
    /**
     *
     * @return the unknown_type
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     *
     * @param unknown_type $role
     */
    public function setRole($role)
    {
        $this->role = $role;
        return $this;
    }

    /**
     *
     * @return the unknown_type
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     *
     * @param unknown_type $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     *
     * @return the unknown_type
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     *
     * @param unknown_type $email
     */
    public function setEmail($email)
    { // @todo check for uniqueness
        $this->email = $email;
        return $this;
    }

    /**
     *
     * @return the unknown_type
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     *
     * @param unknown_type $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     *
     * @return the unknown_type
     */
    public function getMiddleName()
    {
        return $this->middleName;
    }

    /**
     *
     * @param unknown_type $middleName
     */
    public function setMiddleName($middleName)
    {
        $this->middleName = $middleName;
        return $this;
    }

    /**
     *
     * @return the unknown_type
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     *
     * @param unknown_type $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     *
     * @return the unknown_type
     */
    public function getFbUserId()
    {
        return $this->fbUserId;
    }

    /**
     *
     * @param unknown_type $fbUserId
     */
    public function setFbUserId($fbUserId)
    {
        $this->fbUserId = $fbUserId;
        return $this;
    }

    /**
     *
     * @return the unknown_type
     */
    public function getFbAccessToken()
    {
        return $this->fbAccessToken;
    }

    /**
     *
     * @param unknown_type $fbAccessToken
     */
    public function setFbAccessToken($fbAccessToken)
    {
        $this->fbAccessToken = $fbAccessToken;
        return $this;
    }

    /**
     *
     * @return the unknown_type
     */
    public function getFbAccessTokenExpireTs()
    {
        return $this->fbAccessTokenExpireTs;
    }

    /**
     *
     * @param unknown_type $fbAccessTokenExpireTs
     */
    public function setFbAccessTokenExpireTs($fbAccessTokenExpireTs)
    {
        $this->fbAccessTokenExpireTs = $fbAccessTokenExpireTs;
        return $this;
    }

    /**
     *
     * @return the unknown_type
     */
    public function getFbRefreshToken()
    {
        return $this->fbRefreshToken;
    }

    /**
     *
     * @param unknown_type $fbRefreshToken
     */
    public function setFbRefreshToken($fbRefreshToken)
    {
        $this->fbRefreshToken = $fbRefreshToken;
        return $this;
    }

    /**
     *
     * @return the unknown_type
     */
    public function getFbRefreshTokenExpireTs()
    {
        return $this->fbRefreshTokenExpireTs;
    }

    /**
     *
     * @param unknown_type $fbRefreshTokenExpireTs
     */
    public function setFbRefreshTokenExpireTs($fbRefreshTokenExpireTs)
    {
        $this->fbRefreshTokenExpireTs = $fbRefreshTokenExpireTs;
        return $this;
    }

    private function addNewUserStamp($newUserId)
    {
        $this->addId($newUserId);
        $this->addTs();
        $this->statId(10);
    }

    private function modUserStamp($adminId)
    {
        $this->ModId($adminId);
    }

}
