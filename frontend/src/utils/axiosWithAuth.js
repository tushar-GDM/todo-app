import axios from 'axios';

export const axiosWithAuth = (token) =>
  axios.create({
    baseURL: process.env.REACT_APP_API_URL,
    headers: {
      Authorization: `Bearer ${token}`
    }
  });
