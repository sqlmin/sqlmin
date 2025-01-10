import os
import json
import sqlite3
from http.server import HTTPServer, BaseHTTPRequestHandler

# Database paths
databases = [
    os.path.join(os.path.dirname(__file__), '..', 'chinook.sqlite'),
    os.path.join(os.path.dirname(__file__), '..', 'northwind.db'),
]

class DatabaseHandler(BaseHTTPRequestHandler):
    def do_POST(self):
        response = {}

        # Read the request body
        content_length = int(self.headers['Content-Length'])
        post_data = self.rfile.read(content_length)
        data = json.loads(post_data.decode('utf-8'))

        try:
            if data['type'] == 'execute':
                db_path = databases[data['database']]
                query = data['query']

                with sqlite3.connect(db_path) as conn:
                    # Convert results to dictionary
                    conn.row_factory = sqlite3.Row
                    cursor = conn.cursor()
                    cursor.execute(query)
                    rows = cursor.fetchall()
                    response['rows'] = [dict(row) for row in rows]

            elif data['type'] == 'list':
                response['databases'] = [os.path.basename(db) for db in databases]

        except sqlite3.Error as e:
            response['error'] = str(e)
        except Exception as e:
            response['error'] = str(e)

        # Send response
        self.send_response(200)
        self.send_header('Content-Type', 'application/json')
        self.end_headers()

        response_json = json.dumps(response, indent=2)
        self.wfile.write(response_json.encode('utf-8'))

    def do_GET(self):
        # Return 405 Method Not Allowed for GET requests
        self.send_response(405)
        self.end_headers()

if __name__ == '__main__':
    server = HTTPServer(('localhost', 8000), DatabaseHandler)
    print('Starting server on http://localhost:8000')
    server.serve_forever()
