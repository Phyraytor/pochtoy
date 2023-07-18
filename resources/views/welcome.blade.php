<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create short links</title>
    <style type="text/css">
      body {
        margin: 0;
        background: #edeef0;
/*        width: 100%;
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;*/
      }
      main {
        width: 100%;
      }
      .link-form {
        width: 100%;
        padding: 20px 0;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: 64px;
      }
      .link-list {
        list-style: none;
        display: block;
        width: 262px;
        margin: 32px auto 0;
        background: #fff;
        padding: 16px;
        border-radius: 5px;
      }
      .link-list a {
        text-decoration: none;
        color: #000;
      }
      .link-list a:hover {
        color: #0077ff;
      }
      button {
        border: none;
        background: #0077ff;
        color: #fff;
        padding: 10px;
        border-radius: 5px;
        margin-left: 16px;
        cursor: pointer;
      }
      input {
        height: 28px;
        padding: 4px 8px;
        background: #edeef0;
        border: none;
        color: #2c2f32;
        border-radius: 5px;
        outline:none;
      }
    </style>
  </head>
  <body>
    <main>
      <div class="link-form">
        <input id="link" type="text" placeholder="Ссылка">
        <input id="csrf" type="hidden" value={{ csrf_token() }}>
        <button onclick="createSortLink()">Сократить</button>
      </div>
      <ul id="linkList" class=link-list>
        <li></li>
      </ul>
    </main>
  </body>
  <script>
    const api = 'http://127.0.0.1:8000'
    let blockDouble = false
    const clearUl = ul => {
      ul.innerHTML = ''
    }
    const createLi = item => {
      const a = document.createElement("a")
      const text = document.createTextNode(`${api}/${item.shortLink}`)
      a.append(text);
      a.href = item.link
      const li = document.createElement("li")
      li.append(a);
      return li
    }
    const renderList = async () => {
      const response = await fetch(`${api}/links`)
      if (!response.ok) return
      const ul = document.getElementById('linkList')
      clearUl(ul)
      const data = await response.json()
      data.forEach(item => {
        ul.append(createLi(item))
      })
    }
    const createSortLink = async () => {
      const link = document.getElementById('link')
      if (blockDouble) return
      blockDouble = true
      const response = await fetch(`${api}/links`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json;charset=utf-8',
          'Access-Control-Allow-Origin': '*',
          'X-CSRF-TOKEN': document.getElementById('csrf').value
        },
        body: JSON.stringify({
          link: link.value
        })
      })
      blockDouble = false
      response.ok && renderList()
      link.value = ''
    }
    window.onload = () => {
      renderList()
    }
  </script>
</html>
