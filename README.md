Web_PHP_ZF2
==============================

This project is for demonstration and learning for PHP and Zend Framework 2.

Demonstration site at the link below:
link: 
http://zf2.nathanhaley.com

demo user: 
demouser@nathanhaley.com
pass123

demo admin:
demoadmin@nathanhaley.com
pass123


Change log:

2016-08-05: Added initial My Certs Page under Exams menu.

2016-08-04: Implemented duplicate email/username and display name validations for local accounts.

2016-08-02: Added My Home and Admin Tools pages. Contact Us form submission fix. Added edit button on My Profile page.

2016-08-02: Completely new data model implemented. Removed User module, added Account module. Switch storage mechanics for exam attempt answers. Facebook login iterations.

2016-07-27: Initial application of site wide styles and custom theme.

2016-07-26: Facebook login initial error handling. Refactor entity lists into own controllers. Introduce NHUtils module into project. Refactor controllers to inherit from a shared controller.

2016-07-24: Refactoring, user role default fix, minor styling, Initial Facebook login integration.

2016-07-21: Added ui.bootstrap to project and implemented initial carousel to display site change log items.

2016-07-21: Initial Sign in/up/out buttons added. Remove Bootstrap-theme. Add authentication view helper.

2016-07-19: Project list app on homepage: styles updated for all devices, animations added for user friendliness, refresh indicator coordinated to data load.

2016-07-18: Initial version of Angular RESTful app for Projects of interest on homepage.

2016-07-15: Refactoring layout into partials. Fix Exam form layout issues. Pass Exam details data and display name,duration on take page. Minor style updates.

2016-07-14: layout.php,module.configs Refactoring, nav style,titles update. Added page headers. Setup email for Contact Us form. Menu fix for active top level.

2016-07-13: Layout adjustments, alert message style updates and fix for error/danger alerts. Fix 100% scoring issue, 100% score list table badge added.

2016-07-13: Added auto login feature for demoadmin and demouser accounts for visitors on homepage. Added ignore demo accounts for edit and delete. Lists page action fields' header tied to actions array.

2016-07-12: Add showing user's highest score for given test/exam in exam list.

2016-07-11: Add storing user exam/test attempts and answers.

2016-07-09: Add member update/edit account page. Added Role administration to admin account edit. Removed password fields from admin edit user account. Phone fields not required, validations added.

2016-07-08: Implemented list template for exams. Extended list template for dynamic actions designation.

2016-07-08: Created list page shared template including pagination/sort, implemented for user and contact us lists. Bug fix: User list breadcrumb.

2016-07-07: Added order by functionality for admin list page for contact us form submissions.

2016-07-06: Added logging in newly registered users and fix allowing admins to add accounts.

2016-07-05: Adding unit tests for Application Index Controller and User log in.

2016-06-27: Updated to latest Bootstrap css/js. Fix/update nav for current Bootstrap. Login: failure message added, responsive styling started: Contact Us add/edit, User login/add/register/edit. Contextual menu fix;

2016-06-26: Added: Contact Us module with contact form submission, and admin functions for list/edit/delete; breadcrumb; Minor style updates;

2016-06-24: Fixed exam/test scoring, added exam name and user's name to certificate.

2016-06-23: Completed pdf certificate generation and send by email.

2016-06-22: Account menu mods: view/edit/delete only accessible from list page. View page updated with buttons to edit|delete. Some AccountController refactoring for reusable chunks for methods findUserEntity and formAddFields. Button style updates on list pages for users and tests.

2016-06-21: Added debug display switch, initial view and me pages, and initial admin pages for user list, edit, delete

2016-06-20: Completed through the pagination chapter, includes exam, reseting/loading exam from file, exam list page

2016-06-18: Added basic nav, initial ACL, log in/out

2016-06-17: Implemented initial add user account functionality at route: account/add





