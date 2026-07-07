# Quran Khatam

A WordPress plugin for organizing collaborative Quran readings (Khatam al-Quran). Lets communities coordinate 30 people to each read one juz, completing the entire Quran as a group.

## Features

- Create and manage khatam reading sessions with start/end dates
- Participants sign up via a front-end form and get assigned a juz (1–30)
- Participants mark their juz as completed when done
- Live participant table shows progress with status indicators
- Admin dashboard with summary stats, participant management, and khatam CRUD
- Gutenberg blocks for embedding the form and table on any page
- Open Graph settings for social media sharing

## Requirements

- WordPress 5.9 or higher
- PHP 7.2 or higher

## Installation

### Option 1: Upload via WordPress Admin (Recommended)

1. Download the `khatam.zip` file
2. Go to your WordPress admin: **Plugins → Add New → Upload Plugin**
3. Choose the `khatam.zip` file and click **Install Now**
4. Click **Activate Plugin**

### Option 2: Manual Upload via FTP/File Manager

1. Download and unzip `khatam.zip`
2. Upload the `khatam` folder to `/wp-content/plugins/`
3. Go to **Plugins** in WordPress admin and activate **Quran Khatm**

### After Activation

1. The plugin automatically creates the required database tables on activation
2. Go to **Quran Khatm** in the admin sidebar to create your first khatam
3. Add the **Khatam Form** and **Khatam Table** blocks to any page or post using the block editor

## Usage

### For Admins

- **Dashboard** — View all khatams with status, participant counts, and quick actions
- **Add Khatam** — Create a new reading group with name, date range, and optional meeting link
- **Manage Participants** — Add/remove participants, reassign juz numbers, track completion
- **Settings** — Configure Open Graph meta tags for social sharing

### For Participants

1. Visit the page with the Khatam Form block
2. Select "I want to recite" and enter your name and email
3. Submit to get assigned a juz number
4. When finished reading, return to the form, select "I completed recitation," and submit with the same name and email

## Development

### Setup

```bash
git clone <repo-url>
cd khatam
npm install
```

### Build

```bash
npm run build        # Production build
npm run start        # Development build with watch
```

### Create Distributable Zip

```bash
npm run build && npm run plugin-zip
```

This generates `khatam.zip` ready for WordPress installation.

## Authors

Quaid Mehr dil, Moazzam Khan, & Hasham Khan

## License

GPL v2 or later
