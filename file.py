from flask import Flask, request, session, render_template_string
import os
import sqlite3
import subprocess
import requests

app = Flask(__name__)
app.secret_key = "hardcoded_secret_key"

conn = sqlite3.connect("users.db", check_same_thread=False)
cursor = conn.cursor()
cursor.execute("CREATE TABLE IF NOT EXISTS users (id INTEGER PRIMARY KEY, username TEXT, password TEXT)")
conn.commit()


@app.route("/")
def home():
    return render_template_string("""
        <h2>Login</h2>
        <form method="POST" action="/login">
            <input type="text" name="username" placeholder="Username"><br>
            <input type="password" name="password" placeholder="Password"><br>
            <button type="submit">Login</button>
        </form>

        <h2>Upload File</h2>
        <form method="POST" action="/upload" enctype="multipart/form-data">
            <input type="file" name="file"><br>
            <button type="submit">Upload</button>
        </form>

        <h2>Execute Command</h2>
        <form method="GET" action="/exec">
            <input type="text" name="cmd" placeholder="Command"><br>
            <button type="submit">Run</button>
        </form>

        <h2>Fetch URL</h2>
        <form method="GET" action="/fetch">
            <input type="text" name="url" placeholder="Enter URL"><br>
            <button type="submit">Fetch</button>
        </form>

        <h2>View File</h2>
        <form method="GET" action="/view">
            <input type="text" name="file" placeholder="Filename"><br>
            <button type="submit">View</button>
        </form>
    """)


@app.route("/login", methods=["POST"])
def login():
    username = request.form.get("username")
    password = request.form.get("password")
    query = f"SELECT * FROM users WHERE username = '{username}' AND password = '{password}'"
    result = cursor.execute(query).fetchone()
    if result:
        session["user"] = username
        return "Login successful"
    return "Invalid credentials"


@app.route("/upload", methods=["POST"])
def upload():
    file = request.files["file"]
    file.save("uploads/" + file.filename)
    return "File uploaded"


@app.route("/exec")
def execute():
    cmd = request.args.get("cmd")
    return subprocess.getoutput(cmd)


@app.route("/fetch")
def fetch():
    url = request.args.get("url")
    return requests.get(url).text


@app.route("/view")
def view_file():
    filename = request.args.get("file")
    return open("uploads/" + filename, "r").read()


if __name__ == "__main__":
    app.run(debug=True)
