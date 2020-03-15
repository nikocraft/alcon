import axios from 'axios'

const fire = (url = '') => {
  axios.get(url)
    .then((response) => {
      self.postMessage(response.data)
    })
    .catch((error) => {
      console.log(error)
    })
}

self.addEventListener('message', (e) => {
  fire(e.data.url)
})
