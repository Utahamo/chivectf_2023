from flask import Flask, session, redirect, url_for,render_template

app = Flask(__name__)
app.secret_key = 'nanamo_is_so_cute'

# 设置管理员 ID
ADMIN_ID = 1

# 路由1：设置 session cookie
@app.route('/')
def set_session():
    #如果session不为空, 设置为空
    if session.get('GZCTF_Token'):
        session['GZCTF_Token'] = None
    session['hero'] = 0
    return redirect(url_for('index'))

# 路由2：检查 session cookie
@app.route('/index')
def index():
    user_id = session.get('hero')
    if user_id == ADMIN_ID:
        with open('/flag') as f:
            return 'Flag: ' + f.read()
    else:
        return render_template('index.html')
        # return 'www'

if __name__ == '__main__':
    app.run()
