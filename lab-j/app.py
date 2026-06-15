from flask import Flask, render_template, request, redirect
import sqlite3

app = Flask(__name__)

DATABASE = "data.db"


def get_db():
    conn = sqlite3.connect(DATABASE)
    conn.row_factory = sqlite3.Row
    return conn


@app.route("/")
@app.route("/music")
def music_index():

    conn = get_db()

    music_list = conn.execute(
        "SELECT * FROM music ORDER BY id DESC"
    ).fetchall()

    conn.close()

    return render_template(
        "music/index.html",
        music_list=music_list
    )


@app.route("/music/<int:id>")
def music_show(id):

    conn = get_db()

    music = conn.execute(
        "SELECT * FROM music WHERE id=?",
        (id,)
    ).fetchone()

    conn.close()

    return render_template(
        "music/show.html",
        music=music
    )


@app.route("/music/create", methods=["GET", "POST"])
def music_create():

    if request.method == "POST":

        subject = request.form["subject"]
        content = request.form["content"]

        conn = get_db()

        conn.execute(
            "INSERT INTO music(subject, content) VALUES (?, ?)",
            (subject, content)
        )

        conn.commit()
        conn.close()

        return redirect("/music")

    return render_template("music/create.html")


@app.route("/music/<int:id>/edit", methods=["GET", "POST"])
def music_edit(id):
    conn = get_db()
    if request.method == "POST":
        subject = request.form["subject"]
        content = request.form["content"]
        conn.execute(
            """
            UPDATE music
            SET subject=?,
                content=?
            WHERE id=?
            """,
            (subject, content, id)
        )
        conn.commit()
        conn.close()
        return redirect("/music")
    music = conn.execute(
        "SELECT * FROM music WHERE id=?",
        (id,)
    ).fetchone()
    conn.close()
    return render_template(
        "music/edit.html",
        music=music
    )


@app.route("/music/<int:id>/delete")
def music_delete(id):

    conn = get_db()

    conn.execute(
        "DELETE FROM music WHERE id=?",
        (id,)
    )

    conn.commit()
    conn.close()

    return redirect("/music")


if __name__ == "__main__":
    app.run(
        host="localhost",
        port=57830,
        debug=True
    )



