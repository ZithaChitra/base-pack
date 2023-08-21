const authUri       = process.env.MIX_AUTH_SERVER + 'oauth/'
const redirectUri   = process.env.MIX_APP_URL + '/login'
const id            = process.env.MIX_PKCE_CLIENT_ID

/* 
    CURRENT IMPLEMENTATION: 
      - redirect to auth-server and get authenticated everytime this page is loaded
    TODO: 
      - Check first if token exists and if it hasn't expired. And if there's a need, 
        redirect to auth-server and get authenticated.
*/


document.addEventListener('sessionCreated', function(){
    console.log('session created')
    window.location = '/dashboard'
})


let url  = window.location.href
url      = new URL(url).searchParams
let size = Array.from(url).length
console.log(`size: ${size}`)

if(!size){
  buildAuthorizeEndpointAndRedirect()
}

let isCode = url.has('code')
let isRef  = url.has('ref')
if(isCode){
    getToken()
}

let isAcc = url.has('tkn')
if(isAcc){
    console.log('authenticated using microsoft')
    document.cookie = `msb_cstm_jwt=${url.get('tkn')}` 
    document.addEventListener('livewire:load', function(){
      Livewire.emit('authenticated')
    })
}


if(isRef){ 
  document.cookie = `ref=${url.get('ref')}`
}

let isError = url.has('error')
if(isError){
    let txt_msftError = document.getElementById('msftError')
    txt_msftError.innerText = 'An error occurred while trying to login through microsoft. Please try to login again'
}



function getBase(uri){
  let index = uri.indexOf('/')
  index     += 2
  let base  = uri.substring(index)
  index     = base.indexOf('/')
  base      = base.substring(0, index)
  return base

}




async function generateChallenge(verifier) {
    function sha256(plain) {
      const encoder = new TextEncoder()
      const data    = encoder.encode(plain)
  
      return window.crypto.subtle.digest('SHA-256', data)
    }
  
    function base64URLEncode(string) {
      return btoa(String.fromCharCode.apply(null, new Uint8Array(string)))
        .replace(/\+/g, '-')
        .replace(/\//g, '_')
        .replace(/=/g, '')
    }
  
    const hashed = await sha256(verifier)
    return base64URLEncode(hashed)
  }
  
  
//   function createRandomString(num) {
//     const array = new Uint32Array(28)
//     window.crypto.getRandomValues(array)
  
//     return Array.from(array, (item) => `0${item.toString(16)}`.substr(-2)).join(
//       ''
//     )
// }


function createRandomString(num) {
    const array = new Uint32Array(28)
    window.crypto.getRandomValues(array)
    return Array.from(array, (item) => `0${item.toString(16)}`.substr(-2)).join(
      ''
    )
}


async function buildAuthorizeEndpointAndRedirect() {
    const host      = authUri + 'authorize/'
    const verifier  = createRandomString(43)
    const state     = createRandomString(50)
    let challenge   = await generateChallenge(verifier)

    // Build endpoint
    const endpoint = `${host}?
        client_id=${id}&
        redirect_uri=${redirectUri}&
        response_type=code&
        scope=&
        state=${state}&
        code_challenge=${challenge}&
        code_challenge_method=S256`
  
    localStorage.setItem('verifier', verifier)

    window.location = endpoint
}

async function getAppToken(tkn){
  let host =  process.env.MIX_AUTH_SERVER + 'api/jwt/' + id
  let response = null
  try {
    response = await fetch(host, {
        method: 'GET',
        headers: {
          'Authorization': `Bearer ${tkn}`
        },
    })

    const data = await response.json()
    response = data['token']

  } catch (e) {
    console.log(e)
  }

  return response
}


async function getToken() {
    const host      =  authUri + 'token'
    const grantType = 'authorization_code'
    const verifier  = localStorage.getItem('verifier')
  
    // Get code from query params
    const urlParams = new URLSearchParams(window.location.search)
    const code      = urlParams.get('code')

    const params = `grant_type=${grantType}&client_id=${id}&redirect_uri=${redirectUri}&code_verifier=${verifier}&code=${code}`
  

    try {
        const response = await fetch(host, {
            method: 'POST',
            headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: params,
        })
  
        const data = await response.json()
        if(data){
            let resp = await getAppToken(data['access_token'])
            document.cookie = `msb_cstm_jwt=${resp}`;
            console.log('using native login')
            Livewire.emit('authenticated')
            document.addEventListener('livewire:load', function(){
              Livewire.emit('authenticated')
            })
        }

        localStorage.removeItem('verifier')
        // window.location = '/msftlogin'
  
  
    } catch (e) {
      console.log(e)
    }
}