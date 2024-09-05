# Automated Audio File Saving and JSON File Generation

## Automatically Saved Audio Files

Audio files are automatically saved in the following format:

- **File Format**: `m4a`
- **Filename Format**: `ProgramName/Year/Month/Day`

### Example

- `ProgramName/2024/09/06.m4a`

## JSON File Generation

A JSON file containing the download timestamps is automatically generated each month. This file helps manage audio files by recording their download times.

### JSON File Structure

The JSON file is saved in the following format:

```json
{
  "year": "2024",
  "nendoo: "2024",
  "month": "09",
  "files": [
    {
      "file_name": "ProgramName/2024/09/06.m4a",
      "download_time": "2024-09-06T12:34:56Z"
    }
    // Additional file information will be added here
  ]
}
```
