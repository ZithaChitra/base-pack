[Laravel Package Development](https://www.laravelpackage.com/)

**IMPORTANT**.
Add the 'web' middleware for livewire updates to work. \
If your routes don't have the web middleware, a session won't start. Therefore, any Livewire update will not see any session exist and thus think the session has expired.