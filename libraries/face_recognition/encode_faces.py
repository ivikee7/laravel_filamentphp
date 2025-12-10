import face_recognition
import numpy as np
import os
import pickle
from pathlib import Path
import config # <-- IMPORT CONFIG

# --- CONFIGURATION ---
# Use values from config.py
KNOWN_FACES_DIR = config.KNOWN_FACES_DIR
ENCODING_FILE = config.ENCODING_FILE
# --------------------

known_encodings = []
known_ids = []
print(f"Starting face encoding process from directory: {KNOWN_FACES_DIR}...")

# 1. Verification of Directory Existence
if not os.path.isdir(KNOWN_FACES_DIR):
    print(f"FATAL ERROR: Directory not found or inaccessible: {KNOWN_FACES_DIR}")
    exit()

# 2. Iterate through all files inside the known_faces directory
for filename in os.listdir(KNOWN_FACES_DIR):
    # Skip hidden files or files that are not images
    if filename.startswith('.') or not (filename.lower().endswith(('.png', '.jpg', '.jpeg'))):
        continue

    # Filename must start with the numeric User ID, followed by an underscore (e.g., 12_JohnDoe.jpg)
    parts = filename.split('_', 1)
    if len(parts) < 2:
        print(f"  - WARNING: Skipping {filename}. Filename must start with 'ID_NAME.ext'.")
        continue

    # Extract the User ID
    try:
        user_id = int(parts[0])
    except ValueError:
        print(f"  - WARNING: Skipping {filename}. First part ('{parts[0]}') is not a valid integer User ID.")
        continue

    # 3. Process the image
    image_path = Path(KNOWN_FACES_DIR) / filename

    try:
        # Check permissions before loading
        if not os.access(image_path, os.R_OK):
             print(f"  - PERMISSION ERROR: Cannot read file {filename}. Check file permissions.")
             continue

        image = face_recognition.load_image_file(image_path)
        face_locations = face_recognition.face_locations(image)

        if face_locations:
            # Calculate the encoding vector (using only the first face found)
            encoding = face_recognition.face_encodings(image, face_locations)[0]

            known_encodings.append(encoding)
            known_ids.append(user_id)
            print(f"  - Encoded {filename} (User ID: {user_id})")
        else:
            print(f"  - WARNING: No face found in {filename}. Skipping.")

    except Exception as e:
        print(f"  - ERROR processing {filename}: {e}")

print("\nEncoding complete.")
print(f"Total faces encoded: {len(known_encodings)}")

# 4. Save the encodings and IDs to a persistent file
if known_encodings:
    with open(ENCODING_FILE, 'wb') as f:
        data = {"encodings": known_encodings, "ids": known_ids}
        f.write(pickle.dumps(data))

    print(f"Successfully saved encodings to {ENCODING_FILE}")
else:
    print("No valid faces were encoded. Check your image paths and quality.")
