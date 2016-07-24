<?php
namespace User\Model\Entity;

use Zend\Form\Annotation;
use User\Model\PasswordAwareInterface;
use Zend\Crypt\Password\PasswordInterface;

/**
 * @Annotation\Name("users")
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ClassMethods")
 *
 * @Entity @Table(name="users")
 */
class User implements PasswordAwareInterface
{
    /**
     * @Annotation\Exclude()
     *
     * @Id @GeneratedValue @Column(type="integer")
     */
    protected $id;

    /**
    * @Annotation\Type("Zend\Form\Element\Email")
    * @Annotation\Validator({"name":"EmailAddress"})
    * @Annotation\Options({"label":"Email:"})
    * @Annotation\Validator({"name":"StringLength","options":{"min":6,"max":255}})
    * @Annotation\Attributes({"type":"email","required": true,"placeholder": "Email Address..."})
    * @Annotation\Flags({"priority": "600"})
    *
    * @Column(type="string")
    */
    protected $email;

    /**
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Options({"label":"Name:"})
     * @Annotation\Attributes({"required": true,"placeholder":"Type name..."})
     * @Annotation\Validator({"name":"StringLength","options":{"min":1,"max":255}})
     * @Annotation\Flags({"priority": "500"})
     *
     * @Column(type="string")
     */
    protected $name;

    /**
     * @Annotation\Type("Zend\Form\Element\Select")
     * @Annotation\Required({"required":"true"})
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Options({"label":"Role:",
     *                      "value_options":{"member":"Member","admin":"Administrator"}})
     * @Annotation\Validator({"name":"InArray","options":{"haystack":{"member","admin"},
     *                                          "messages":{"notInArray":"Role does not exist"}}})
     * @Annotation\Attributes({"value":"member"})
     * @Annotation\Flags({"priority": "400"})
     *
     * @Column(type="string")
     */
    protected $role;

    /**
     * @Annotation\Type("Zend\Form\Element\Password")
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"StringLength","options":{"min":4,"max":100}})
     * @Annotation\Options({"label":"Password:", "priority": "400"})
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
     * @Annotation\Options({"label":"Your Phone Number:"})
     * @Annotation\Validator({"name":"StringLength","options":{"min":5,"max":255}})
     * @Annotation\Validator({"name":"RegEx",
     *                          "options": {
     *                              "pattern": "/^[\d-\/]+$/",
     *                              "messages":
     *                                  {"regexNotMatch":"Only numbers, 0-9, and dashes '-'"}
     *                           }
     *                       })
     * @Annotation\Attributes({"type":"tel","pattern":"^[\d-/]+$","messages":{"regexNotMatch":"Only numbers, 0-9, and dashes '-'"}})
     * @Annotation\Flags({"priority": "100"})
     *
     * @Column(type="string")
     */
    protected $phone;

    /**
     * @Annotation\Exclude()
     *
     * @Column(type="string")
     */
    protected $photo;

    /**
     * @Annotation\Exclude()
     *
     * @Column(type="string")
     */
    protected $fb_user_id;

    /**
     * @Annotation\Exclude()
     *
     * @Column(type="string")
     */
    protected $fb_access_token;

    /**
     * @Annotation\Exclude()
     *
     * @Fb_access_token_expire_dt @GeneratedValue @Column(type="datetime")
     */
    protected $fb_access_token_expire_dt;

    /**
     * @Annotation\Exclude()
     *
     * @Column(type="string")
     */
    protected $fb_refresh_token;

    /**
     * @Annotation\Exclude()
     *
     * @Column(type="datetime")
     */
    protected $fb_refresh_token_expire_dt;

    /**
     * @Annotation\Exclude()
     *
     * @Cdate @GeneratedValue @Column(type="datetime")
     */
    protected $cdate;

    /**
     * @Annotation\Exclude()
     *
     * @Mdate @GeneratedValue @Column(type="datetime")
     */
    protected $mdate;

    /**
     * @Annotation\Exclude()
     * @var PasswordInterface
     */
    protected $passwordAdapter;

    /**
     * @return the $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return the $role
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @return the $email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return the $phone
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param field_type $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param field_type $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

    /**
     * @param field_type $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @param field_type $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return the $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param field_type $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return $photo
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @param field_type $photo
     */
    public function setPhoto($photo)
    {
        if(isset($photo['tmp_name'])) {
            $this->photo = $photo['tmp_name'];
        }
    }

    /**
     * @return $fb_user_id
     */
    public function getFb_user_id()
    {
        return $this->fb_user_id;
    }

    /**
     * @param field_type $fb_user_id
     */
    public function setFb_user_id($fb_user_id)
    {
        $this->fb_user_id = $fb_user_id;
    }

    /**
     * @return $fb_access_token
     */
    public function getFb_access_token()
    {
        return $this->fb_access_token;
    }

    /**
     * @param field_type $fb_access_token
     */
    public function setFb_access_token($fb_access_token)
    {
        $this->fb_access_token = $fb_access_token;
    }

    /**
     * @return $fb_access_token_expire_dt
     */
    public function getFb_access_token_expire_dt()
    {
        return $this->fb_access_token_expire_dt;
    }

    /**
     * @param field_type $fb_access_token_expire_dt
     */
    public function setFb_access_token_expire_dt($fb_access_token_expire_dt)
    {
        $this->fb_access_token_expire_dt = $fb_access_token_expire_dt;
    }

    /**
     * @return $fb_refresh_token
     */
    public function getFb_refresh_token()
    {
        return $this->fb_refresh_token;
    }

    /**
     * @param field_type $fb_refresh_token
     */
    public function setFb_refresh_token($fb_refresh_token)
    {
        $this->fb_refresh_token = $fb_refresh_token;
    }

    /**
     * @return $fb_refresh_token_expire_dt
     */
    public function getFb_refresh_token_expire_dt()
    {
        return $this->fb_refresh_token_expire_dt;
    }

    /**
     * @param field_type $fb_refresh_token_expire_dt
     */
    public function setFb_refresh_token_expire_dt($fb_refresh_token_expire_dt)
    {
        $this->fb_refresh_token_expire_dt = $fb_refresh_token_expire_dt;
    }

    /**
     * @return $cdate
     */
    public function getCdate()
    {
        return $this->cdate;
    }

    /**
     * @param field_type $cdate
     */
    public function setCdate($cdate)
    {
        $this->cdate = $cdate;
    }

    /**
     * @return $mdate
     */
    public function getMdate()
    {
        return $this->mdate;
    }

    /**
     * @param field_type $mdate
     */
    public function setMdate($mdate)
    {
        $this->mdate = $mdate;
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
     * @param string $password clear text password
     * @return boolean
     */
    public function verifyPassword($password)
    {
        return $this->passwordAdapter->verify($password, $this->password);
    }

    /**
     * Verifies if the provided Facebook token matches the stored one.
     *
     * @param string $token in clear text
     * @return boolean
     */
    public function verifyFaceBookAccessToken($token)
    {
        return $token === $this->fb_access_token;
    }

    /**
     * Hashes a password
     * @param string $password
     * @return string
     */
    private function hashPassword($password)
    {
        return $this->passwordAdapter->create($password);
    }

    /**
     * Sets the password adapter
     * @param PasswordInterface $adapter
     */
    public function setPasswordAdapter(PasswordInterface $adapter)
    {
        $this->passwordAdapter = $adapter;
    }

    /**
     * Gets the password adapter
     * @return PasswordInterface
     */
    public function getPasswordAdapter()
    {
        return $this->passwordAdapter;
    }

}
