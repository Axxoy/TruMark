from http.server import SimpleHTTPRequestHandler, HTTPServer
import cgi
import os
import test_classifier

PORT = 65431
DIRECTORY = os.path.expanduser('~/server/signatures/')
FILE_NAME = os.path.join(DIRECTORY, 'received_image.png')
RESPONSE_MESSAGE = "Image not classified successfully!"

class CustomRequestHandler(SimpleHTTPRequestHandler):
    def do_OPTIONS(self):  # Added to handle CORS preflight
        self.send_response(200, "ok")
        self.send_header('Access-Control-Allow-Origin', '*')
        self.send_header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')
        self.send_header('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type')
        self.end_headers()

    def do_POST(self):
        form = cgi.FieldStorage(
            fp=self.rfile,
            headers=self.headers,
            environ={'REQUEST_METHOD': 'POST'}
        )

        file_data = form['file'].file.read()

        # Ensure the directory exists
        if not os.path.exists(DIRECTORY):
            os.makedirs(DIRECTORY)

        with open(FILE_NAME, 'wb') as f:
            f.write(file_data)

        RESPONSE_MESSAGE = test_classifier.classify(FILE_NAME)
        print(RESPONSE_MESSAGE)

        self.send_response(200)
        self.send_header('Access-Control-Allow-Origin', '*')  # Added CORS headers
        self.send_header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')
        self.end_headers()
        self.wfile.write(RESPONSE_MESSAGE.encode())

if __name__ == "__main__":
    with HTTPServer(('0.0.0.0', PORT), CustomRequestHandler) as server:
        print(f"Server started at http://0.0.0.0:{PORT}")
        server.serve_forever()
