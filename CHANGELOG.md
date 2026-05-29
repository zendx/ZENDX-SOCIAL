# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2026-05-29

### Added
- Initial release of ZENDX Social Scheduler
- Multi-platform OAuth support (Facebook, Instagram, Twitter, TikTok, LinkedIn)
- Post creation, scheduling, and publishing
- Social account management
- Analytics tracking (impressions, clicks, engagements)
- Comment management system
- User role-based access control (Admin, Editor)
- Email notifications via SMTP
- Password reset functionality
- Customer chat management
- Campaign and calendar event tracking
- RESTful API endpoints for account listing and comment fetching

### Security
- Session-based authentication
- Password hashing with bcrypt
- Prepared statements for SQL injection prevention
- Input sanitization with htmlspecialchars()
- Role-based access control

### Documentation
- Comprehensive README with setup instructions
- Security policy document
- Contributing guidelines
- Environment configuration templates
- Database schema documentation

### Notes
- Fresh build scaffold suitable for development and learning
- Embedded PHPMailer for SMTP functionality
- Requires further hardening for production use

## Planned for Future Releases

### v1.1.0
- [ ] Token encryption at rest
- [ ] Request rate limiting
- [ ] Comprehensive audit logging
- [ ] Two-factor authentication
- [ ] Advanced analytics dashboard
- [ ] Bulk scheduling tools
- [ ] Post templates/presets

### v1.2.0
- [ ] API key authentication
- [ ] Row-level security for multi-tenant
- [ ] Permission-based access control
- [ ] Media file upload system
- [ ] Post performance comparisons
- [ ] Social listening features
- [ ] Content calendar view

### v2.0.0
- [ ] Microservices architecture
- [ ] Real-time collaboration
- [ ] AI-powered content suggestions
- [ ] Advanced queue system
- [ ] Webhook support
- [ ] GraphQL API
- [ ] Mobile app

## Security Updates

- v1.0.0+: Security fixes applied to releases

Subscribe to releases for security updates.
