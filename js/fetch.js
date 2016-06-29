import 'whatwg-fetch';

export function status(response) {
  if (response.status >= 200 && response.status < 300) {
    return Promise.resolve(response)
  } else {
    return Promise.reject(new Error(response.statusText))
  }
}

export function json(response) {
  return response.json()
}

export default function ( base, path, params, bodyObject = {}, opts = {} ) {

  let headers = new Headers();

  if ( Object.keys(bodyObject).length ) {
    let bodyString = JSON.stringify(Object.assign({}, {
      nonce: TenUpCuration.nonce,
    }, bodyObject ) );
    headers.append( 'Content-Type', 'application/json' );
    Object.assign(params, {
      body: bodyString,
    });
  }

  if( opts.auth ) {
    Object.assign( params, {
      credentials: 'same-origin'
    } );
    headers.append( 'X-WP-NONCE', TenUpCuration.nonce );
  }

  Object.assign(params, {
    headers,
  });

  return fetch( base + path, params)
    .then(status)
    .then(json);
}