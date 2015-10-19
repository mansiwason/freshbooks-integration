Kayako Freshbooks Integration
=======================

This library is maintained by Kayako.

Overview
=======================

FreshBooks is an online invoicing software as a service for time tracking, expense tracking, recurring billing, online payment collection, the ability to mail invoices through the U.S. Post, and support tickets.

This is Kayako app for integrating Kayako version 4.50 with Freshbooks.

So you can create freshbooks billing entry directly from Kayako helpdesk.

Features
=======================

* Freshbooks integration enable Invoicing based on ticket billing hours.
* You can link tickets to specific project and add ticket billing entries direct to Freshbooks using this integration.
* If Kayako login user has same name in Freshbooks then it will automatically get selected while creating time entry from Kayako billing tab
* Once you have created time entry in freshbooks on any ticket, next time you will find freshbooks time entry section enable by default on that ticket
* Kayako admin can anytime disable freshbooks from Admin->Settings->Freshbooks

Supported versions
=======================

Kayako: v4.51.x and above

Installation Steps
=======================
1. Download and extract the Kayako-Freshbooks integration
2. Create a symlink of src/ as freshbooks and place it in /path/to/your/installation/__apps/
3. Go to Admin interface of your helpdesk and click on Apps on left hand side menu
4. Now click on Freshbooks and then click on Install button, this will install this app
5. Now click on Settings option from left side menu and click on Freshbooks
6. You will see freshbooks settings page
7. First you have to enable the freshbooks by selecting yes for first option i.e. "Enable Fresh books (To show or hide freshbooks checkbox on tickets page)"
8. For "API URL (API URL from your FreshBooks account. Copy and paste the entire URL from FreshBooks)" set up a FreshBooks account at www.freshbooks.com
9. Get your API URL and Authentication Token from FreshBooks
10. Click on verify connection button to test if API URL and Authentication Token provided are correct
11. If you see above message then click on Ok button
12. Then select your default project and task, which will be selected by default in Staff CP - Ticket Billing Tab
13. In the last settings option you can set the default note to send to your freshbook time entry, by default Kayako Ticket Id : {[ticket_id]} i.e Kayako Ticket Id : Corresponding ticket id will be send
14. Now click on update button to save your settings
15. Now you will be able to report time on tickets from the ticket page. Go to Staff CP, select any ticket and click on Billing tab
16. Enter billable hours and write any note which you want to send with your default note set in Admin Interface settings section.
17. Select a user, then select a project and then a task and click on Insert to create entry in freshbooks.
18. The hours you enter will be immediately visible within FreshBooks on the project you selected.