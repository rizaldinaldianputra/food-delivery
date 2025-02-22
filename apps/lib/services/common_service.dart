import 'dart:convert';
import 'package:dio/dio.dart' as diopackage;
import 'package:dio/dio.dart';
import 'package:flutter/material.dart';
import 'package:fluttertoast/fluttertoast.dart';
import 'package:fooddelivery/config/constant.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:go_router/go_router.dart';

class CommonService {
  static const String url = API_URL;
  static BaseOptions opts = BaseOptions(
      baseUrl: url,
      responseType: ResponseType.json,
      contentType: 'application/json');

  late Dio _dio;

  CommonService(BuildContext context) {
    _dio = Dio(opts);
    _dio.interceptors.add(getInterceptorWrapper(context));
  }

  InterceptorsWrapper getInterceptorWrapper(BuildContext context) {
    return InterceptorsWrapper(
      onError: (error, handler) async {
        if (error.response == null) {
          Fluttertoast.showToast(
            msg: "Network Error",
            toastLength: Toast.LENGTH_SHORT,
            backgroundColor: Colors.red,
            textColor: Colors.white,
            fontSize: 13,
          );
          return handler.next(error);
        }

        if (error.response!.statusCode == 403) {
          // Handle forbidden access if needed
          return handler.next(error);
        }

        if (error.response!.statusCode == 401) {
          Fluttertoast.showToast(
              msg: error.response!.data['message'],
              toastLength: Toast.LENGTH_SHORT,
              backgroundColor: Colors.red,
              textColor: Colors.white,
              fontSize: 13);
          context.pushReplacementNamed('login');

          return;
        }

        if (error.response!.statusCode == 500) {
          // context.pushReplacementNamed('login');
          return;
        }

        return handler.next(error);
      },
      onRequest: (options, handler) async {
        final token = await _getToken();
        if (token != null) {
          options.headers.addAll({"Authorization": "Bearer $token"});
        }
        // Tambahkan header Accept
        options.headers.addAll({"Accept": "application/json"});
        return handler.next(options);
      },
      onResponse: (response, handler) async {
        if (response.statusCode == 401) {
          await _handleUnauthorized(context);
        }

        return handler.next(response);
      },
    );
  }

  Future<void> _handleUnauthorized(BuildContext context) async {
    final sharedPreferences = await SharedPreferences.getInstance();
    await sharedPreferences.remove("user");
    await sharedPreferences.remove("token");

    return context.goNamed('login');
  }

  Future<String?> _getToken() async {
    final sharedPreferences = await SharedPreferences.getInstance();
    return sharedPreferences.getString("token");
  }

  Future<diopackage.Response> getHTTP(String url) async {
    try {
      return await _dio.get(url);
    } on diopackage.DioException catch (e) {
      return Future.error(e);
    }
  }

  Future<diopackage.Response> postHTTP(String url, dynamic data) async {
    try {
      return await _dio.post(url, data: jsonEncode(data));
    } on diopackage.DioException catch (e) {
      return Future.error(_getErrorMessage(e));
    }
  }

  Future<diopackage.Response> postLogout(String url) async {
    try {
      return await _dio.post(url);
    } on diopackage.DioException catch (e) {
      return Future.error(_getErrorMessage(e));
    }
  }

  Future<diopackage.Response> postHTTPFormData(
      String url, FormData data) async {
    try {
      return await _dio.post(url, data: data);
    } on diopackage.DioException catch (e) {
      return Future.error(_getErrorMessage(e));
    }
  }

  Future<diopackage.Response> putHTTP(String url, dynamic data) async {
    try {
      return await _dio.put(url, data: jsonEncode(data));
    } on diopackage.DioException catch (e) {
      return Future.error(_getErrorMessage(e));
    }
  }

  Future<diopackage.Response> deleteHTTP(String url) async {
    try {
      return await _dio.delete(url);
    } on diopackage.DioException catch (e) {
      return Future.error(_getErrorMessage(e));
    }
  }

  String _getErrorMessage(diopackage.DioException e) {
    if (e.response == null) {
      return "Network Error";
    } else {
      return e.response!.data['message'] ?? "Unknown error";
    }
  }
}
