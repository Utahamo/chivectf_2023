from flask import Flask
from flask import render_template
from flask import request
from flask import render_template_string
app = Flask(__name__)
@app.route('/',methods=['GET', 'POST'])
def test():
    template = '''
            <h3>我超, %s</h3>
    ''' %(request.url)
    return render_template_string(template)
if __name__ == '__main__':
    app.debug = True
    app.run()