import axios from 'axios'
import axiosClient from '@/stores/axios'

// export const getRequest = async (store, uri, idPassed) => {
//   try {
//     const id = idPassed ?? '';
//     const url = `/${uri}/${id}`
//     const response = await axiosClient.get(url);
//     store.data.value = response.data;
//   } catch (e) {
//     console.error(e);
//   }
// }

export const getRequest = async (uri, idPassed) => {
  try {
    const id = idPassed ?? '';
    const url = `/${uri}/${id}`
    console.log(url, idPassed, 'EVK');

    const response = await axiosClient.get(url);
    console.log(url, response, 'EVK');
    return response
  } catch (e) {
    console.log('Error Occured WHILE FETCHING THE Data', e)
  }
}


export const postRequest = async (gebi, uri,) => {
  try {
    console.log(gebi, "with uri", uri, 'GEBI')
    const response = await axiosClient.post(uri, gebi)
    return response.data;
  } catch (err) {
    console.error('Error creating request request', err)
  }
}

export const putRequest = async (updatedData, uri,) => {
  try {
    console.log(updatedData, "with uri", uri, 'UPDATE')
    console.log(`${uri}/${updatedData.id}`, updatedData);
    const response = await axiosClient.put(`${uri}/${updatedData.id}`, updatedData)
    console.log(response, 'returned data')
    return response.data;
  } catch (err) {
    console.error('Error creating request request', err)
  }
}

export const deleteRequest = async (uri, id) => {
  try {
    const url = `/${uri}/${id}`;
    console.log(`Deleting from ${url}`);
    const response = await axiosClient.delete(url);
    return response.data;
  } catch (err) {
    console.error('Error deleting request', err);
    throw err;
  }
}
