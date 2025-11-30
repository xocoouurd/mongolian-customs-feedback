/**
 * Google Apps Script for Mongolian Customs Feedback Form
 *
 * INSTRUCTIONS:
 * 1. Create a new Google Sheet
 * 2. Go to Extensions > Apps Script
 * 3. Delete any existing code and paste this entire file
 * 4. Click Deploy > New deployment
 * 5. Select "Web app" as the type
 * 6. Set "Execute as" to "Me"
 * 7. Set "Who has access" to "Anyone"
 * 8. Click Deploy and copy the Web App URL
 * 9. Paste the URL into index.html where indicated
 */

// Handle POST requests from the feedback form
function doPost(e) {
  try {
    var sheet = SpreadsheetApp.getActiveSpreadsheet().getActiveSheet();
    var data = JSON.parse(e.postData.contents);

    // Validate required fields
    if (!data.type || !data.message) {
      return createResponse({ success: false, error: 'Missing required fields' });
    }

    // Append new row with timestamp, type, and message
    sheet.appendRow([
      new Date(),      // Огноо (Timestamp)
      data.type,       // Төрөл (Type: Санал/Гомдол/Өргөдөл)
      data.message     // Мессеж (Message)
    ]);

    return createResponse({ success: true });

  } catch (error) {
    return createResponse({ success: false, error: error.toString() });
  }
}

// Handle GET requests (for testing)
function doGet(e) {
  return createResponse({ status: 'ok', message: 'Feedback API is running' });
}

// Create JSON response with CORS headers
function createResponse(data) {
  return ContentService
    .createTextOutput(JSON.stringify(data))
    .setMimeType(ContentService.MimeType.JSON);
}
