from flask import Flask, request, jsonify
import cv2
import face_recognition
import numpy as np
import pickle
from PIL import Image
import io
import time

# --- NEW: Import configuration from config.py ---
import config

app = Flask(__name__)

# --- CONFIGURATION ---
# Use values from config.py
ENCODING_FILE = config.ENCODING_FILE # Now fetched from config.py
TOLERANCE = config.TOLERANCE        # Now fetched from config.py
# --------------------

known_encodings = []
known_ids = []

# Load known faces from the file created by encode_faces.py
try:
    # Uses the path defined in config.py
    with open(ENCODING_FILE, 'rb') as f:
        data = pickle.loads(f.read())
        known_encodings = data['encodings']
        known_ids = [int(i) for i in data['ids']]
    print(f"Loaded {len(known_ids)} known face encodings from {ENCODING_FILE}.")
except FileNotFoundError:
    print("FATAL: Encodings file not found. Ensure encode_faces.py was run.")
    known_encodings = []
    known_ids = []

# --- Helper function for frame processing (TOLERANCE used here) ---

def recognize_frame(frame):
    # Resize frame for faster processing (to 1/4 size)
    small_frame = cv2.resize(frame, (0, 0), fx=0.25, fy=0.25)
    rgb_small_frame = small_frame[:, :, ::-1]

    face_locations = face_recognition.face_locations(rgb_small_frame)
    if not face_locations:
        return {"status": "failure", "user_id": 0, "confidence": 0.0, "message": "No face detected"}

    face_encodings = face_recognition.face_encodings(rgb_small_frame, face_locations)

    best_match_id = None
    best_confidence = 0.0

    for face_encoding in face_encodings:
        face_distances = face_recognition.face_distance(known_encodings, face_encoding)
        best_match_index = np.argmin(face_distances)

        # Uses the global TOLERANCE defined from config.py
        if face_distances[best_match_index] < TOLERANCE:
            current_id = known_ids[best_match_index]
            current_confidence = 1.0 - face_distances[best_match_index]

            if current_confidence > best_confidence:
                best_confidence = current_confidence
                best_match_id = current_id

    if best_match_id is not None:
        return {
            "status": "success",
            "user_id": best_match_id,
            "confidence": round(best_confidence, 4),
            "timestamp": time.time()
        }
    return {"status": "failure", "user_id": 0, "confidence": 0.0,
            "message": "Face detected, but no confident match found."}

# --- DUAL-PURPOSE ROUTE ---

@app.route('/recognize', methods=['POST'])
def recognize_handler():
    # --- 1. CHECK FOR JSON BODY (RTSP Request from Laravel) ---
    if request.is_json:
        data = request.get_json()
        rtsp_url = data.get('rtsp_url')

        if rtsp_url:
            # Execute RTSP Logic
            start_time = time.time()

            # OpenCV constants
            video_capture = cv2.VideoCapture(rtsp_url, cv2.CAP_FFMPEG)
            video_capture.set(cv2.CAP_PROP_BUFFERSIZE, 1)

            if not video_capture.isOpened():
                return jsonify({"status": "error", "message": f"Failed to open RTSP stream at {rtsp_url}"}), 500

            ret, frame = video_capture.read()
            video_capture.release()

            if not ret:
                return jsonify({"status": "failure", "message": "Could not read a frame from the RTSP stream."}), 500

            result = recognize_frame(frame)
            end_time = time.time()
            print(f"INFO: Recognized {result.get('status')} via RTSP in {end_time - start_time:.2f} seconds.")
            return jsonify(result)


    # --- 2. FALLBACK TO FILE UPLOAD (Original Logic) ---
    if 'image' not in request.files:
        return jsonify({"status": "error", "message": "No image file provided (expected file or JSON body)."}), 400

    file = request.files['image']

    try:
        # Read image from file stream
        image = Image.open(io.BytesIO(file.read()))
        image_np = np.array(image.convert('RGB'))

        # Find all faces in the image
        face_locations = face_recognition.face_locations(image_np)

        if not face_locations:
            return jsonify({"status": "failure", "message": "No face detected in the image."})

        face_encodings = face_recognition.face_encodings(image_np, face_locations)

        best_match_id = None
        best_confidence = 0.0

        for face_encoding in face_encodings:
            face_distances = face_recognition.face_distance(known_encodings, face_encoding)
            best_match_index = np.argmin(face_distances)

            if face_distances[best_match_index] < TOLERANCE:
                current_id = known_ids[best_match_index]
                current_confidence = 1.0 - face_distances[best_match_index]

                if current_confidence > best_confidence:
                    best_confidence = current_confidence
                    best_match_id = current_id

        if best_match_id is not None:
            return jsonify({
                "status": "success",
                "user_id": best_match_id,
                "confidence": round(best_confidence, 3)
            })

        return jsonify({"status": "failure", "message": "Face detected but no match found in database."})

    except Exception as e:
        print(f"Recognition Error: {e}")
        return jsonify({"status": "error", "message": "Internal processing error."}), 500
