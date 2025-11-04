import { showToast } from "../toast.js";

function getToken() {
  return localStorage.getItem("token");
}

// POST REQUEST
export async function postRequest(url, data, useAuth = false) {
  const headers = {
    "Content-Type": "application/json",
  };

  if (useAuth) {
    const token = getToken();
    if (token) 
        headers["Authorization"] = `Bearer ${token}`;
  }

  try {
    const response = await fetch(url, {
      method: "POST",
      headers,
      body: JSON.stringify(data),
    });

    const result = await response.json();

    if (!response.ok) {
      showToast(result.error || "Request failed", "error");
      return null;
    }

    return result;
  } catch (error) {
    console.error("Request error:", error);
    showToast("Server error. Please try again later.", "error");
    return null;
  }
}

// GET REQUEST
export async function getRequest(url, useAuth = false) {
  const headers = {};

  if (useAuth) {
    const token = getToken();
    if (token) 
        headers["Authorization"] = `Bearer ${token}`;
  }

  try {
    const response = await fetch(url, { method: "GET", headers });
    const result = await response.json();

    if (!response.ok) {
      showToast(result.error || "Request failed", "error");
      return null;
    }

    return result;
  } catch (error) {
    console.error("Request error:", error);
    showToast("Server error. Please try again later.", "error");
    return null;
  }
}
