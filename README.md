<h1 align="center">🎵 SEW – Müzik Dinleme Uygulaması (Backend Odaklı)</h1>

<p align="center">
  Gerçek zamanlı müzik dinleme deneyimi için hazırlanmış sunucu tabanlı bir prototip 🎧<br>
  <strong>PHP Backend + JWT Token + REST API</strong>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/build-stable-brightgreen?style=flat-square">
  <img src="https://img.shields.io/badge/backend-PHP-blue?style=flat-square">
  <img src="https://img.shields.io/badge/token-JWT-red?style=flat-square">
</p>

---

## 🎯 Amaç

SEW, modern bir müzik platformunun temel işlevlerini sunmakla birlikte, özellikle **güvenli kullanıcı doğrulama** ve **sunucu taraflı veri işleme** üzerinde yoğunlaşan bir projedir.

Bu proje ile amacım:
- ✅ Token tabanlı güvenli giriş/çıkış sistemi kurmak
- ✅ PHP ile RESTful API mimarisi tasarlamak
- ✅ Kullanıcı, şarkı, sanatçı işlemlerini güvenlikli şekilde backend mantığıyla oturtmak
- ✅ Gerçek dünya senaryolarına uygun bir backend yapı sunmak

---

## 🧩 Öne Çıkan Özellikler

| Özellik | Açıklama |
|--------|----------|
| 🔐 JWT Authentication | Giriş yapan kullanıcıya özel token üretimi ve middleware üzerinden doğrulama |
| 🎵 Müzik API'si | Şarkılar, sanatçılar, kategoriler ve çalma listelerine REST API erişimi |
| 👤 Kullanıcı Yönetimi | Kayıt, giriş, çıkış ve token yenileme işlemleri |
| 📁 Basit Yapılandırma | Kolay anlaşılır, modüler PHP kod yapısı |
| ⚡ Performans Odaklı | Hafif, hızlı yanıtlar veren optimize backend kurgusu |

---

## 💻 Örnek API Kullanımı

### Kullanıcı Girişi

```http
POST /api/login
Content-Type: application/json

{
  "email": "erdem@ornek.com",
  "password": "123456"
}
```
Geri dönüş:
```
{
  "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
  "user": {
    "id": 1,
    "name": "Erdem"
  }
}
```
🔗 Canlı Demo / Geliştirme Notları
-Şu anda sadece backend dosyaları paylaşıldı (PHP)
-Frontend kısmı temel düzeyde ve test amaçlıdır
-Kullanıcı ve içerik sistemi tamamen çalışır durumdadır.

👨‍💻 Geliştirici
Yapımcı: Erdem Skywalker
Odak Noktası: Güvenli, modüler ve sürdürülebilir backend geliştirme


📣 Katkı ve Destek
Yıldızla ⭐ | Forkla 🍴 | Issue aç 🔧 | Geri bildirim bırak 💬
Bu proje, staj arayışım sürecinde teknik yeteneklerimi göstermek için hazırlandı.
Backend odaklı bir geliştirici olarak profesyonel ekiplerle çalışmak ve kendimi daha da geliştirmek istiyorum.
