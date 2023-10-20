const express = require('express');
const bodyParser = require('body-parser');
const fs = require('fs');
const port = 5000;

function merge(target, source) {
    for (let key in source) {
        if (key in source && key in target) {
            merge(target[key], source[key])
        } else {
            target[key] = source[key]
        }
    }
}

const app = express();
app.use(bodyParser.json());

// 路由：主页
app.get('/', (req, res) => {
    res.send('/parse, /hint');

});

app.get('/hint', (req, res) => {
    res.send('原型链污染, {"isAdmin":"nanamo"}');
});

// 路由：解析 JSON 对象
app.all('/parse', (req, res) => {
    let json = JSON.stringify(req.body);
    let admin = {};
    // 解析 JSON 对象
    let user = JSON.parse(json);
    let user2 = {}

    merge(user2, user);

    // 检查 isAdmin 属性
    if (admin.isAdmin) {
        // 防止覆盖之后的__proto__会导致后面的merge函数报错
        delete admin.__proto__.isAdmin;
        fs.readFile('/flag', 'utf8', (err, data) => {
            if (err) {
                console.error(err);
                return res.send('Error reading file');
            }
            return res.send(`<img src="https://cdn.jsdelivr.net/gh/Utahamo/momopic/image2023-9/202309232148078.jpg"><br>哼, 才不想给你flag呢 ${data}`);
        });
    } else {
        return res.send(`<img src="https://cdn.jsdelivr.net/gh/Utahamo/momopic/image2023-9/202309232145131.jpg"><br>真是杂鱼呢, 不是管理员还想要flag吗`);
    }
});

app.listen(port, () => {
    console.log(`App running on http://localhost:${port}`);
});
