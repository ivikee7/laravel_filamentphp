from flask import Flask, request, jsonify
import cv2
import face_recognition
import numpy as np
import pickle
import time

app = Flask(__name__)

# --- CONFIGURATION ---
ENCODING_FILE = "encodings.pickle"
TOLERANCE = 0.6
# --------------------

known_encodings = []
known_ids = []

# Load encodings once when the Gunicorn worker starts
try:
    with open(ENCODING_FILE, 'rb') as f:
        data = pickle.loads(f.read())
        known_encodings = data['encodings']
        known_ids = [int(i) for i in data['ids']]
    print(f"INFO: Loaded {len(known_ids)} known face encodings.")
except FileNotFoundError:
    print("FATAL: Encodings file not found. Ensure encode_faces.py was run.")
    known_encodings = []
    known_ids = []


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


@app.route('/recognize_rtsp', methods=['POST'])
def recognize_rtsp():
    """Receives RTSP URL, captures one frame, recognizes, and returns JSON."""

    # 1. Get the RTSP URL from the POST request (e.g., from Laravel)
    data = request.get_json()
    rtsp_url = data.get('rtsp_url')

    if not rtsp_url:
        return jsonify({"status": "error", "message": "RTSP URL not provided in JSON body."}), 400

    start_time = time.time()

    # 2. Open the stream for a single frame
    # Use FFMPEG backend for stability, and set buffer size to 1 for the newest frame
    video_capture = cv2.VideoCapture(rtsp_url, cv2.CAP_FFMPEG)
    video_capture.set(cv2.CAP_PROP_BUFFERSIZE, 1)

    if not video_capture.isOpened():
        return jsonify({"status": "error", "message": f"Failed to open RTSP stream at {rtsp_url}"}), 500

    # 3. Read ONE frame
    ret, frame = video_capture.read()

    # 4. Release the stream immediately to free up resources/locks
    video_capture.release()

    if not ret:
        return jsonify({"status": "failure", "message": "Could not read a frame from the RTSP stream."}), 500

    # 5. Process the frame
    result = recognize_frame(frame)
    end_time = time.time()

    print(f"INFO: Recognized {result.get('status')} in {end_time - start_time:.2f} seconds.")

    return jsonify(result)

# NOTE: Since you are using Gunicorn, remove any if __name__ == '__main__': app.run(...) code.
