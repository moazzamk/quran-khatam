=== Quran Khatm ===
Contributors: Moazzam Khan, Quaid & Hasham Khan
Tags: khatm, Quran khatm, Quran recitation, recitation, email reminders
Requires at least: 5.9
Tested up to: 6.5
Stable tag: 1.0.0
Requires PHP: 7.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A plugin for collaborative Quran Khatm recitations with automatic email reminders.

== Description ==

Quran Khatm facilitates collaborative Quran reading (Khatam al-Quran). The Quran has 30 sections (juz), and this plugin organizes 30 people to each read one juz within a defined time period, completing the entire Quran as a group.

**Features:**

* Create khatam sessions with start/end dates and optional meeting info
* Visitors sign up via a Gutenberg block form and get assigned a juz (1-30)
* Participants can mark their juz as completed
* Front-end table displays participant list and progress
* **Automatic email reminders** sent to participants who haven't finished their juz before the deadline
* Configurable reminder schedule (how many days before end, how often to repeat)
* Multiple email transport support (WordPress wp_mail, SMTP/Gmail)
* Rich text email editor with placeholder support

== Installation ==

= From Gutenberg Editor: =

1. Go to the WordPress Block/Gutenberg Editor
2. Search for **khatam**
3. Click on **Khatam Form** or **Khatam Table** to add the block

= Download & Upload: =

1. Download the **Quran Khatm** plugin (.zip file)
2. In your admin area, go to the Plugins menu and click on **Add New**
3. Click on **Upload Plugin** and choose the .zip file and click on **Install Now**
4. Activate the plugin

= Manually: =

1. Download and upload the **Quran Khatm** plugin to the **/wp-content/plugins/** directory
2. Activate the plugin through the **Plugins** menu in WordPress

== Email Reminders ==

= Overview =

The plugin can automatically send email reminders to participants who have not completed their assigned juz as the khatam deadline approaches.

= Configuration =

Navigate to **Quran Khatm > Settings** in the WordPress admin area. Under the **Email Reminders** section you can configure:

* **Enable/Disable** — Turn reminders on or off
* **Days before end (X)** — How many days before the khatam end date to start sending reminders (e.g. 2 means reminders start 2 days before)
* **Send every (Y) days** — How often to repeat (e.g. 1 means every day once the window starts)
* **Email Transport** — Choose between WordPress default (wp_mail/PHP mail) or SMTP
* **SMTP Settings** — Host, port, encryption, username, and password (for Gmail use an App Password)
* **From Address** — The sender email address
* **Subject** — Email subject line (supports placeholders)
* **Body** — Rich text email body (supports placeholders)

= Placeholders =

You can use the following placeholders in both the subject and body. They are replaced with actual values when the email is sent:

* `{days_remaining}` — Number of days until the khatam ends
* `{participant_name}` — Full name of the participant
* `{first_name}` — Participant's first name
* `{last_name}` — Participant's last name
* `{juz_number}` — Juz number assigned to the participant
* `{khatam_end_date}` — End date of the khatam (formatted)
* `{khatam_name}` — Name of the khatam

= Example =

If X = 2 and Y = 1, a khatam ending on July 10 will send reminders on July 8, July 9, and July 10 to anyone who still has status = not completed.

== Cron Setup (Important) ==

WordPress uses a "pseudo-cron" system (wp-cron) that only runs when someone visits the site. If your site has low traffic, reminders may not fire on time.

**We strongly recommend setting up a real system cron job** to ensure reminders are sent reliably.

= Step 1: Disable WordPress pseudo-cron =

Add this line to your `wp-config.php` file (before the "That's all, stop editing!" comment):

`define('DISABLE_WP_CRON', true);`

= Step 2: Add a system cron job =

Set up a cron job that hits `wp-cron.php` every hour (or more frequently if you prefer). Below are instructions for popular hosting platforms.

= cPanel (HostGator, Bluehost, SiteGround, A2 Hosting, etc.) =

1. Log in to your **cPanel** dashboard
2. Under the **Advanced** section, click **Cron Jobs**
3. Set the schedule to **Once Per Hour** (or "Every 15 minutes" for more precision)
4. In the **Command** field, enter:

`wget -q -O /dev/null https://yourdomain.com/wp-cron.php?doing_wp_cron >/dev/null 2>&1`

Or alternatively using curl:

`curl -s https://yourdomain.com/wp-cron.php?doing_wp_cron >/dev/null 2>&1`

5. Click **Add New Cron Job**

= GoDaddy (Managed WordPress / cPanel) =

**If you have cPanel access:**
Follow the same steps as above under the cPanel section.

**If on Managed WordPress (no cPanel):**

1. Log in to your GoDaddy account and go to **My Products > Managed WordPress**
2. Click **Settings** on your site
3. GoDaddy Managed WordPress runs wp-cron on page loads by default. For a dedicated cron, you may need to:
   - Use a third-party cron service like [cron-job.org](https://cron-job.org) (free)
   - Set it to ping `https://yourdomain.com/wp-cron.php?doing_wp_cron` every hour

= HostGator =

1. Log in to **cPanel** (accessible from your HostGator dashboard under Hosting > cPanel)
2. Search for **Cron Jobs** in the cPanel search bar
3. Under **Add New Cron Job**, select **Once Per Hour**
4. In the command field enter:

`/usr/bin/curl -s https://yourdomain.com/wp-cron.php?doing_wp_cron >/dev/null 2>&1`

5. Click **Add New Cron Job**

= Cloudways =

1. Log in to the **Cloudways Platform**
2. Go to **Application > Cron Job Management**
3. Click **Add New Cron Job** (Advanced)
4. Set the schedule to run every hour: `0 * * * *`
5. Command:

`curl -s https://yourdomain.com/wp-cron.php?doing_wp_cron >/dev/null 2>&1`

6. Save

= Linux Server (SSH / VPS / Dedicated) =

If you have SSH access to your server, run:

`crontab -e`

Then add this line:

`0 * * * * curl -s https://yourdomain.com/wp-cron.php?doing_wp_cron >/dev/null 2>&1`

Or using WP-CLI (if installed):

`0 * * * * cd /path/to/wordpress && wp cron event run --due-now >/dev/null 2>&1`

= Using a Free External Cron Service =

If your hosting does not support cron jobs, you can use a free service:

1. Sign up at [cron-job.org](https://cron-job.org) or [EasyCron](https://www.easycron.com)
2. Create a new cron job pointing to: `https://yourdomain.com/wp-cron.php?doing_wp_cron`
3. Set the interval to every 60 minutes (or 15 minutes for more precision)

**Note:** Replace `yourdomain.com` with your actual domain in all examples above.

== Frequently Asked Questions ==

= Is Quran Khatm free? =

Yes, Quran Khatm is a free Gutenberg block plugin.

= Does it work with any WordPress theme? =

Yes, it will work with any standard WordPress theme.

= Do participants need a WordPress account? =

No. Participants sign up with their name and email — no WordPress account required.

= How does the email reminder system work? =

The plugin uses WordPress Cron to check daily if any participants haven't completed their juz and if the khatam end date is within the configured reminder window. If so, it sends emails via the configured transport (wp_mail or SMTP).

= What if my site gets no traffic? =

WordPress cron only runs on page visits by default. Set up a real system cron job (see the Cron Setup section above) to ensure reminders fire on schedule.

= Can I use Gmail to send emails? =

Yes. Select "SMTP" as the transport, enter `smtp.gmail.com` as the host, port `587`, TLS encryption, your Gmail address as username, and a Google App Password as the password. Do not use your regular Gmail password — generate an App Password at myaccount.google.com > Security > 2-Step Verification > App passwords.

= What placeholders can I use in the email? =

See the Placeholders section above. You can use `{days_remaining}`, `{participant_name}`, `{first_name}`, `{last_name}`, `{juz_number}`, `{khatam_end_date}`, and `{khatam_name}` in both the subject and body.

== Screenshots ==

1. Khatam signup form on the front end
2. Participant table showing juz assignments and completion status
3. Email reminder settings in the admin area

== Changelog ==

= 1.0.0 =
* Added email reminder system with configurable schedule
* Added transport abstraction (wp_mail + SMTP)
* Added admin settings page for email configuration
* Added rich text editor for email body with placeholder support
* Added email log table to prevent duplicate sends
* Added cron scheduling for automated reminders

= 0.1.0 =
* Initial release