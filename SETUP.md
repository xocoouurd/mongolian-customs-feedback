# Google Sheets Setup Guide / Тохиргооны заавар

This guide explains how to set up Google Sheets to receive form submissions.

## Step 1: Create a Google Sheet

1. Go to [Google Sheets](https://sheets.google.com)
2. Click **Blank** to create a new spreadsheet
3. Name it: `Customs Feedback` (or any name you prefer)
4. In the first row, add these headers:
   - A1: `Огноо`
   - B1: `Төрөл`
   - C1: `Мессеж`

## Step 2: Create the Apps Script

1. In your Google Sheet, click **Extensions** > **Apps Script**
2. Delete any existing code in the editor
3. Open the file `google-apps-script.js` from this folder
4. Copy ALL the code and paste it into the Apps Script editor
5. Click the **Save** icon (or Ctrl+S)
6. Name the project: `Feedback Handler`

## Step 3: Deploy as Web App

1. Click **Deploy** > **New deployment**
2. Click the gear icon next to "Select type" and choose **Web app**
3. Fill in:
   - **Description**: `Feedback Form Handler`
   - **Execute as**: `Me`
   - **Who has access**: `Anyone`
4. Click **Deploy**
5. Click **Authorize access** and follow the prompts
   - Choose your Google account
   - Click "Advanced" > "Go to Feedback Handler (unsafe)"
   - Click "Allow"
6. **COPY the Web App URL** - you'll need this!

## Step 4: Configure the HTML file

1. Open `index.html` in a text editor
2. Find this line near the top of the `<script>` section:
   ```javascript
   const GOOGLE_SCRIPT_URL = 'YOUR_GOOGLE_APPS_SCRIPT_URL_HERE';
   ```
3. Replace `YOUR_GOOGLE_APPS_SCRIPT_URL_HERE` with your Web App URL
4. Save the file

## Step 5: Test

1. Open `index.html` in a web browser
2. Click one of the buttons (Санал, Гомдол, or Өргөдөл)
3. Enter a test message and click "Илгээх"
4. Check your Google Sheet - a new row should appear!

---

## Troubleshooting

### "Тохиргоо дутуу байна" error
- Make sure you replaced the placeholder URL in `index.html`

### Submissions not appearing in Google Sheet
- Verify the Web App URL is correct
- Make sure the Apps Script is deployed as a Web App
- Check that "Who has access" is set to "Anyone"

### Authorization errors
- You may need to re-authorize when you update the script
- Go to Apps Script > Deploy > Manage deployments > Edit > Create new version

---

## Updating the Script

If you modify `google-apps-script.js`:

1. Copy the new code to Apps Script
2. Click **Deploy** > **Manage deployments**
3. Click the **Edit** (pencil) icon
4. Under "Version", select **New version**
5. Click **Deploy**

The URL stays the same, so you don't need to update `index.html`.
