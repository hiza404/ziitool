<?php

return [
    'categories' => [
        'image' => [
            'name' => 'Xử lý Ảnh & Tệp',
            'desc' => 'Chuyển đổi định dạng, nén ảnh, resize hàng loạt không giảm chất lượng',
            'icon' => 'image',
            'color' => 'indigo',
        ],
        'dev' => [
            'name' => 'Developer & Lập trình',
            'desc' => 'Format JSON, SQL, CSS, mã hóa Base64 và mã băm bảo mật',
            'icon' => 'code',
            'color' => 'emerald',
        ],
        'finance' => [
            'name' => 'Tài chính & Văn phòng',
            'desc' => 'Tính thuế TNCN chuẩn Việt Nam và tính lãi kép tích lũy đầu tư',
            'icon' => 'calculator',
            'color' => 'amber',
        ],
        'graphics' => [
            'name' => 'Đồ họa & Mockup',
            'desc' => 'Tạo mockup thiết bị chuyên nghiệp, tạo mã QR và trích xuất bảng màu',
            'icon' => 'sparkles',
            'color' => 'rose',
        ],
    ],

    'list' => [
        // 1. Image Converter
        'chuyen-doi-anh' => [
            'slug' => 'chuyen-doi-anh',
            'title' => 'Chuyển Đổi Định Dạng Ảnh',
            'category' => 'image',
            'badge' => 'Phổ biến',
            'short_desc' => 'Chuyển đổi qua lại giữa PNG, JPG, WebP, SVG, BMP, ICO tức thì trên trình duyệt.',
            'icon' => 'refresh-cw',
            'seo_title' => 'Chuyển Đổi Định Dạng Ảnh Miễn Phí (PNG, JPG, WebP, SVG) Trực Tuyến',
            'seo_desc' => 'Công cụ chuyển đổi định dạng ảnh online miễn phí, bảo mật 100% không tải lên máy chủ. Chuyển đổi PNG sang WebP, JPG sang PNG trong 1 giây.',
            'keywords' => 'chuyển đổi ảnh, convert png to webp, jpg sang png, svg sang png online, image converter free',
            'how_to' => [
                'Kéo thả hoặc tải ảnh bạn muốn chuyển đổi từ máy tính/điện thoại.',
                'Chọn định dạng xuất mong muốn (PNG, JPEG, WebP, BMP, ICO).',
                'Tùy chỉnh chất lượng xuất ảnh (tùy chọn).',
                'Nhấn nút "Tải Về Ảnh" để lưu ảnh đã chuyển đổi tức thì.',
            ],
            'faq' => [
                [
                    'q' => 'Công cụ chuyển đổi ảnh này có giới hạn kích thước hay số lượng không?',
                    'a' => 'Hoàn toàn không! Vì ảnh được xử lý trực tiếp trên trình duyệt của bạn (Client-Side), bạn có thể chuyển đổi bao nhiêu ảnh tùy thích với tốc độ tối đa.',
                ],
                [
                    'q' => 'Dữ liệu ảnh của tôi có bị tải lên máy chủ không?',
                    'a' => 'Không. 100% quá trình chuyển đổi diễn ra trong bộ nhớ trình duyệt, ảnh của bạn không bao giờ rời khỏi thiết bị, đảm bảo quyền riêng tư tuyệt đối.',
                ],
            ],
        ],

        // 2. Image Compressor
        'nen-anh' => [
            'slug' => 'nen-anh',
            'title' => 'Nén Ảnh Không Giảm Chất Lượng',
            'category' => 'image',
            'badge' => 'Hot',
            'short_desc' => 'Giảm 60-90% dung lượng ảnh JPG, PNG, WebP mà vẫn giữ nguyên độ sắc nét.',
            'icon' => 'minimize-2',
            'seo_title' => 'Nén Ảnh Online Miễn Phí - Giảm Dung Lượng Ảnh Không Mờ Sắc Nét',
            'seo_desc' => 'Công cụ nén ảnh trực tuyến miễn phí tốt nhất. Giảm dung lượng ảnh WebP, PNG, JPG lên đến 90%, so sánh Before/After trực quan, tải về ngay.',
            'keywords' => 'nén ảnh online, giảm dung lượng ảnh, nén ảnh webp, nén ảnh không giảm chất lượng, compress image online',
            'how_to' => [
                'Tải ảnh cần giảm dung lượng vào khung xử lý.',
                'Điều chỉnh thanh trượt mức nén (từ 10% đến 100%).',
                'Xem trước kích thước Before & After và tỉ lệ dung lượng tiết kiệm được.',
                'Bấm "Tải Ảnh Đã Nén" để lưu về máy.',
            ],
            'faq' => [
                [
                    'q' => 'Nén ảnh bằng công cụ này có làm vỡ hạt hay mờ ảnh không?',
                    'a' => 'Thuật toán nén thông minh tối ưu hóa bảng màu và cấu trúc nén điểm ảnh, giữ cho mắt thường không nhận thấy sự suy giảm chất lượng ở mức nén 70-85%.',
                ],
            ],
        ],

        // 3. Batch Image Resizer
        'resize-anh' => [
            'slug' => 'resize-anh',
            'title' => 'Resize Ảnh Hàng Loạt',
            'category' => 'image',
            'badge' => 'Tiện ích',
            'short_desc' => 'Đổi kích thước hàng chục ảnh cùng lúc theo pixel hoặc tỉ lệ %, xuất file ZIP.',
            'icon' => 'maximize-2',
            'seo_title' => 'Resize Ảnh Hàng Loạt Online Miễn Phí - Tải File ZIP Tức Thì',
            'seo_desc' => 'Thay đổi kích thước nhiều ảnh cùng lúc trực tuyến. Giữ nguyên tỉ lệ khung hình (Aspect Ratio), điều chỉnh chiều rộng/cao linh hoạt, tải về file nén ZIP.',
            'keywords' => 'resize ảnh hàng loạt, thay đổi kích thước ảnh online, đổi pixel ảnh, batch image resizer free',
            'how_to' => [
                'Chọn một hoặc nhiều ảnh cùng lúc để tải lên.',
                'Chọn chế độ resize: theo Kích thước cố định (Width/Height) hoặc theo Phần trăm (%).',
                'Tích chọn "Khóa tỉ lệ khung hình" để ảnh không bị méo.',
                'Bấm "Bắt đầu Resize" và tải về từng ảnh hoặc trọn bộ file ZIP.',
            ],
            'faq' => [
                [
                    'q' => 'Tôi có thể tải lên cùng lúc 50 ảnh để resize không?',
                    'a' => 'Được! Công cụ hỗ trợ xử lý đa tệp song song và nén tự động thành file ZIP tiện lợi.',
                ],
            ],
        ],

        // 4. JSON Formatter & Validator
        'json-formatter' => [
            'slug' => 'json-formatter',
            'title' => 'JSON Beautifier & Validator',
            'category' => 'dev',
            'badge' => 'Dev Tool',
            'short_desc' => 'Định dạng, thụt lề 2/4 spaces, nén minify và kiểm tra lỗi cú pháp JSON.',
            'icon' => 'file-code',
            'seo_title' => 'JSON Formatter & Validator Online - Format Làm Đẹp & Kiểm Tra Lỗi JSON',
            'seo_desc' => 'Công cụ JSON Beautifier và Validator trực tuyến nhanh nhất. Thụt lề 2 spaces, 4 spaces, minify JSON 1 dòng, phát hiện lỗi cú pháp chính xác từng dòng.',
            'keywords' => 'json formatter, json beautifier, json validator online, format json online, minify json',
            'how_to' => [
                'Dán chuỗi JSON của bạn vào ô nhập liệu bên trái.',
                'Chọn kiểu thụt lề: 2 khoảng trắng, 4 khoảng trắng hoặc Tab.',
                'Bấm "Beautify" để làm đẹp hoặc "Minify" để nén thành 1 dòng.',
                'Bấm "Copy" để sao chép kết quả hoặc "Tải File .json" về máy.',
            ],
            'faq' => [
                [
                    'q' => 'Công cụ này xử lý file JSON dung lượng lớn (10MB+) được không?',
                    'a' => 'Có! Engine xử lý JavaScript tối ưu chạy trực tiếp trên máy của bạn nên xử lý các file JSON lớn cực nhanh mà không bị nghẽn mạng.',
                ],
            ],
        ],

        // 5. SQL Formatter & Minifier
        'sql-formatter' => [
            'slug' => 'sql-formatter',
            'title' => 'SQL Formatter & Minifier',
            'category' => 'dev',
            'badge' => 'Dev Tool',
            'short_desc' => 'Chuẩn hóa, thụt lề câu lệnh SQL phức tạp (SELECT, JOIN, WHERE) và nén gọn query.',
            'icon' => 'database',
            'seo_title' => 'SQL Formatter Online - Làm Đẹp & Định Dạng Câu Lệnh SQL Chuẩn',
            'seo_desc' => 'Công cụ format SQL query trực tuyến miễn phí. Tự động căn chỉnh từ khóa SELECT, FROM, WHERE, GROUP BY, JOIN. Hỗ trợ MySQL, PostgreSQL, SQLite, Oracle.',
            'keywords' => 'sql formatter, format sql query online, sql beautifier, làm đẹp câu lệnh sql, minify sql',
            'how_to' => [
                'Dán câu truy vấn SQL lộn xộn vào khung nhập.',
                'Bấm "Format SQL" để tự động ngắt dòng và thụt lề khoa học.',
                'Bấm "Minify SQL" nếu cần nén câu lệnh để gắn vào code ứng dụng.',
                'Bấm "Copy" để sử dụng ngay.',
            ],
            'faq' => [
                [
                    'q' => 'Hỗ trợ các hệ quản trị CSDL nào?',
                    'a' => 'Hỗ trợ chuẩn ANSI SQL, MySQL, PostgreSQL, SQLite, Microsoft SQL Server, Oracle.',
                ],
            ],
        ],

        // 6. CSS Formatter & Minifier
        'css-formatter' => [
            'slug' => 'css-formatter',
            'title' => 'CSS Beautifier & Minifier',
            'category' => 'dev',
            'badge' => 'Dev Tool',
            'short_desc' => 'Làm đẹp mã CSS dễ đọc hoặc nén sạch CSS để tăng tốc độ tải trang web.',
            'icon' => 'layout',
            'seo_title' => 'CSS Formatter & Minifier Online - Nén & Làm Đẹp Code CSS Miễn Phí',
            'seo_desc' => 'Công cụ định dạng và nén CSS trực tuyến miễn phí. Tối ưu hóa file CSS cho website của bạn, loại bỏ comment và khoảng trắng thừa, đo lường % dung lượng giảm.',
            'keywords' => 'css formatter, css minifier online, nén css, làm đẹp css, format css online',
            'how_to' => [
                'Dán đoạn code CSS vào khung soạn thảo.',
                'Bấm "Beautify CSS" để định dạng cấu trúc rõ ràng.',
                'Bấm "Minify CSS" để nén tối đa cho môi trường production.',
                'Sao chép kết quả chỉ với 1 cú nhấp chuột.',
            ],
            'faq' => [
                [
                    'q' => 'Nén CSS có làm hỏng thuộc tính CSS3 hay Media Query không?',
                    'a' => 'Không! Trình nén tuân thủ nghiêm ngặt cú pháp CSS3 hiện đại bao gồm cả Flexbox, Grid, CSS Variables và `@media`.',
                ],
            ],
        ],

        // 7. Base64 & Hash Tool
        'base64-hash' => [
            'slug' => 'base64-hash',
            'title' => 'Base64 & Hash Generator',
            'category' => 'dev',
            'badge' => 'Bảo mật',
            'short_desc' => 'Mã hóa/giải mã Base64 chuỗi & tệp; tạo mã băm MD5, SHA-1, SHA-256, SHA-512.',
            'icon' => 'shield-check',
            'seo_title' => 'Base64 Encode/Decode & MD5 SHA256 Hash Generator Online',
            'seo_desc' => 'Công cụ mã hóa và giải mã Base64 văn bản và tệp tin, tính mã băm MD5, SHA-1, SHA-256, SHA-512 chuẩn xác bằng Web Crypto API bảo mật cao.',
            'keywords' => 'base64 encode online, base64 decode, md5 hash generator, sha256 online, tạo mã băm',
            'how_to' => [
                'Chọn tab tính năng: "Base64 Text", "Base64 File" hoặc "Tạo Hash".',
                'Nhập nội dung hoặc kéo thả tệp vào khung.',
                'Xem kết quả mã hóa/giải mã hoặc các mã băm tương ứng được sinh ra tức thì.',
                'Sao chép mã băm cần dùng chỉ với một nút bấm.',
            ],
            'faq' => [
                [
                    'q' => 'Mã băm MD5 hay SHA-256 có bị lưu lại trên server không?',
                    'a' => 'Không! Toàn bộ hàm băm được tính toán bởi Web Crypto API bản địa ngay trên trình duyệt của bạn.',
                ],
            ],
        ],

        // 8. Custom QR Code Generator
        'tao-ma-qr' => [
            'slug' => 'tao-ma-qr',
            'title' => 'Tạo Mã QR Tùy Biến Đẹp',
            'category' => 'graphics',
            'badge' => 'Đồ họa',
            'short_desc' => 'Tạo QR URL, WiFi, VietQR, vCard với màu sắc riêng, chèn Logo ở giữa.',
            'icon' => 'qr-code',
            'seo_title' => 'Tạo Mã QR Code Online Đẹp Tùy Biến Màu Sắc & Chèn Logo Miễn Phí',
            'seo_desc' => 'Trình tạo mã QR tùy biến hàng đầu. Hỗ trợ tạo QR link website, mật khẩu WiFi, vCard danh bạ, số tài khoản VietQR, tùy chỉnh màu nền, chèn logo thương hiệu.',
            'keywords' => 'tạo mã qr tùy biến, qr code generator with logo, tạo qr wifi, tạo qr vietqr, qr code đẹp',
            'how_to' => [
                'Chọn loại nội dung: Đường dẫn (URL), Văn bản, WiFi, Danh bạ (vCard) hoặc VietQR.',
                'Nhập thông tin tương ứng vào các trường dữ liệu.',
                'Tùy chỉnh màu sắc mã QR, màu nền và tải logo thương hiệu của bạn lên.',
                'Chọn kích thước và nhấn "Tải Về Ảnh QR (PNG/SVG)".',
            ],
            'faq' => [
                [
                    'q' => 'Mã QR tạo ra có bị hết hạn sau thời gian sử dụng không?',
                    'a' => 'Không bao giờ hết hạn! Đây là mã QR tĩnh (Static QR), thông tin được khắc trực tiếp vào ma trận điểm nên tồn tại vĩnh viễn.',
                ],
            ],
        ],

        // 9. Personal Income Tax Calculator (TNCN)
        'tinh-thue-tncn' => [
            'slug' => 'tinh-thue-tncn',
            'title' => 'Tính Thuế TNCN Chuẩn Mới',
            'category' => 'finance',
            'badge' => 'Tài chính',
            'short_desc' => 'Tính thuế thu nhập cá nhân Gross <-> Net, giảm trừ gia cảnh, chi tiết 7 bậc thuế.',
            'icon' => 'dollar-sign',
            'seo_title' => 'Công Cụ Tính Thuế Thu Nhập Cá Nhân (TNCN) Mới Nhất Chuẩn Luật',
            'seo_desc' => 'Bảng tính thuế TNCN online chuẩn xác theo quy định mới nhất. Giảm trừ bản thân 11 triệu, phụ thuộc 4.4 triệu, bảo hiểm bắt buộc 10.5%, chi tiết bảng tính từng bậc.',
            'keywords' => 'tính thuế tncn online, công cụ tính thuế thu nhập cá nhân, tính lương gross sang net, bảng thuế lũy tiến từng phần',
            'how_to' => [
                'Nhập mức Lương (Gross hoặc Net) mỗi tháng của bạn.',
                'Nhập số lượng người phụ thuộc (nếu có).',
                'Tùy chỉnh mức đóng bảo hiểm (mặc định BHXH 8%, BHYT 1.5%, BHTN 1%).',
                'Xem bảng phân tích trực quan: Thu nhập trước thuế, Các khoản giảm trừ, Thuế từng bậc và Thu nhập thực nhận.',
            ],
            'faq' => [
                [
                    'q' => 'Mức giảm trừ gia cảnh hiện tại là bao nhiêu?',
                    'a' => 'Theo quy định hiện hành, mức giảm trừ cho bản thân người nộp thuế là 11.000.000 VNĐ/tháng (132 triệu/năm), mức giảm trừ cho mỗi người phụ thuộc là 4.400.000 VNĐ/tháng.',
                ],
            ],
        ],

        // 10. Compound Interest Calculator
        'tinh-lai-kep' => [
            'slug' => 'tinh-lai-kep',
            'title' => 'Tính Lãi Kép & Tích Lũy Đầu Tư',
            'category' => 'finance',
            'badge' => 'Đầu tư',
            'short_desc' => 'Mô phỏng sức mạnh lãi kép, tích lũy tiền gửi định kỳ với biểu đồ tăng trưởng.',
            'icon' => 'trending-up',
            'seo_title' => 'Công Cụ Tính Lãi Kép & Lập Kế Hoạch Đầu Tư Trực Quan Online',
            'seo_desc' => 'Tính lãi kép trực tuyến miễn phí. Nhập số vốn ban đầu, số tiền tích lũy hàng tháng, lãi suất và thời gian đầu tư để thấy sức mạnh kỳ quan thứ 8 của thế giới qua biểu đồ sinh động.',
            'keywords' => 'tính lãi kép online, công cụ tính lãi kép, compound interest calculator vietnam, lập kế hoạch tích lũy tài chính',
            'how_to' => [
                'Nhập số tiền vốn ban đầu bạn có.',
                'Nhập số tiền bạn dự định góp thêm định kỳ mỗi tháng.',
                'Nhập lãi suất kỳ vọng (%/năm) và số năm đầu tư tích lũy.',
                'Xem biểu đồ tăng trưởng so sánh giữa "Tiền Vốn Bỏ Ra" và "Lợi Nhuận Lãi Kép Sinh Ra".',
            ],
            'faq' => [
                [
                    'q' => 'Lãi kép hoạt động như thế nào?',
                    'a' => 'Lãi kép là việc tái đầu tư số tiền lãi nhận được vào số vốn gốc ban đầu, từ đó số tiền lãi ở kỳ tiếp theo sẽ được sinh ra từ cả vốn lẫn lãi cũ, tạo hiệu ứng quả cầu tuyết theo thời gian.',
                ],
            ],
        ],

        // 11. Device Mockup Generator
        'tao-mockup-thiet-bi' => [
            'slug' => 'tao-mockup-thiet-bi',
            'title' => 'Tạo Mockup Thiết Bị Sang Xịn',
            'category' => 'graphics',
            'badge' => 'Đồ họa',
            'short_desc' => 'Lồng ảnh màn hình vào khung MacBook, iPhone, iPad, Safari với nền gradient.',
            'icon' => 'monitor',
            'seo_title' => 'Tạo Mockup Thiết Bị Online Miễn Phí - iPhone, MacBook, iPad, Browser',
            'seo_desc' => 'Công cụ tạo mockup thiết bị chuyên nghiệp trực tuyến. Ghép ảnh chụp màn hình vào iPhone 15 Pro, MacBook Pro, Safari Browser, tùy biến gradient nền, xuất ảnh 2K.',
            'keywords' => 'tạo mockup thiết bị online, device mockup generator, mockup iphone macbook, ghép ảnh vào khung máy tính điện thoại',
            'how_to' => [
                'Chọn mẫu thiết bị: iPhone 15 Pro, MacBook Pro, iPad hoặc Khung trình duyệt Safari.',
                'Tải ảnh chụp màn hình ứng dụng hoặc website của bạn lên.',
                'Tùy chỉnh góc bo, độ bóng đổ (Shadow) và màu nền gradient/minimal.',
                'Nhấn "Tải Ảnh Mockup (HD/2K)" để sử dụng trong bài viết hoặc portfolio.',
            ],
            'faq' => [
                [
                    'q' => 'Ảnh xuất ra có gắn watermark (hình mờ) bản quyền không?',
                    'a' => 'Hoàn toàn sạch 100%, không dính bất kỳ logo hay watermark nào!',
                ],
            ],
        ],

        // 12. Color Palette Extractor
        'trich-xuat-bang-mau' => [
            'slug' => 'trich-xuat-bang-mau',
            'title' => 'Trích Xuất Bảng Màu Từ Ảnh',
            'category' => 'graphics',
            'badge' => 'Sáng tạo',
            'short_desc' => 'Tự động lấy 6-10 mã màu chủ đạo từ hình ảnh, xuất mã HEX, RGB, CSS Variables.',
            'icon' => 'palette',
            'seo_title' => 'Trích Xuất Bảng Màu Từ Ảnh Online - Color Palette Extractor Miễn Phí',
            'seo_desc' => 'Tải ảnh bất kỳ lên và tự động tạo bảng màu phối đẹp mắt. Lấy mã màu HEX, RGB, HSL chủ đạo, 1-click copy và xuất file cấu hình CSS Variables hoặc Tailwind tiện lợi.',
            'keywords' => 'trích xuất màu từ ảnh, color palette extractor, lấy mã màu từ ảnh online, bảng màu ảnh, image color picker',
            'how_to' => [
                'Tải ảnh thiết kế, phong cảnh hoặc banner bất kỳ lên.',
                'Thuật toán sẽ tự động phân tích các dải màu nổi bật nhất trong ảnh.',
                'Nhấp chuột vào bất kỳ ô màu nào để copy mã HEX tức thì.',
                'Xuất bảng màu sang định dạng CSS Variables, Tailwind Config hoặc ảnh Palette Card.',
            ],
            'faq' => [
                [
                    'q' => 'Độ chính xác của việc nhận diện màu như thế nào?',
                    'a' => 'Thuật toán phân cụm điểm ảnh (Color Quantization) quét hàng nghìn điểm ảnh mẫu để chọn lọc ra các gam màu đại diện và hài hòa nhất.',
                ],
            ],
        ],

        // 13. PDF to Word Converter
        'pdf-sang-word' => [
            'slug' => 'pdf-sang-word',
            'title' => 'Chuyển PDF Sang Word',
            'category' => 'finance',
            'badge' => 'Hot Nhất',
            'short_desc' => 'Chuyển đổi tệp PDF sang tài liệu Microsoft Word (.docx) chuẩn xác, giữ nguyên văn bản và bố cục.',
            'icon' => 'file-text',
            'seo_title' => 'Chuyển PDF Sang Word Online Miễn Phí (.DOCX) - Bảo Mật & Chuẩn Định Dạng',
            'seo_desc' => 'Công cụ chuyển đổi PDF sang Word trực tuyến miễn phí 100%, bảo mật tuyệt đối không tải tài liệu lên máy chủ. Xuất file DOCX chuẩn định dạng, sửa đổi dễ dàng trên Microsoft Word.',
            'keywords' => 'chuyển pdf sang word, convert pdf to word, pdf sang docx, chuyen pdf qua word mien phi, pdf to docx online',
            'how_to' => [
                'Chọn hoặc kéo thả tệp PDF cần chuyển đổi từ máy tính hoặc điện thoại.',
                'Chọn tùy chọn trang (Toàn bộ tài liệu hoặc dải trang cụ thể).',
                'Hệ thống tự động phân tích văn bản, phông chữ và ngắt dòng trong bộ nhớ trình duyệt.',
                'Nhấn nút "Tải File Word (.docx)" để nhận ngay tài liệu có thể chỉnh sửa.',
            ],
            'faq' => [
                [
                    'q' => 'Tài liệu PDF của tôi có bị lưu lại trên máy chủ không?',
                    'a' => 'Tuyệt đối không! Toàn bộ quá trình đọc PDF và tạo file Word DOCX được thực thi 100% trong trình duyệt của bạn (Client-Side), tài liệu không bao giờ rời khỏi thiết bị.',
                ],
                [
                    'q' => 'Tệp Word xuất ra có tương thích với Microsoft Office và Google Docs không?',
                    'a' => 'Có. File xuất ra theo định dạng Office OpenXML tiêu chuẩn (.docx) nên mở mượt mà trên Microsoft Word, Google Docs, WPS Office và LibreOffice.',
                ],
            ],
        ],

        // 14. Convert to Excel (XLSX)
        'chuyen-sang-excel' => [
            'slug' => 'chuyen-sang-excel',
            'title' => 'Chuyển Đổi Sang Excel (.XLSX)',
            'category' => 'finance',
            'badge' => 'Văn phòng',
            'short_desc' => 'Chuyển đổi bảng biểu từ PDF, CSV, JSON, HTML Table sang bảng tính Excel (.xlsx) chuẩn xác.',
            'icon' => 'table',
            'seo_title' => 'Chuyển Đổi PDF, CSV, JSON Sang Excel (.XLSX) Trực Tuyến Miễn Phí',
            'seo_desc' => 'Chuyển đổi nhanh chóng tài liệu PDF có bảng số liệu, tệp CSV, dữ liệu JSON hoặc bảng HTML thành bảng tính Excel XLSX hoàn chỉnh, hỗ trợ phân tách cột thông minh.',
            'keywords' => 'chuyển pdf sang excel, csv sang excel, json to excel, html table sang excel, convert to xlsx online free',
            'how_to' => [
                'Chọn nguồn dữ liệu: Tệp PDF (có bảng biểu), Tệp CSV/TSV, Dữ liệu JSON hoặc mã HTML Table.',
                'Hệ thống tự động nhận diện cấu trúc hàng/cột và kiểu dữ liệu (số, ngày tháng, chuỗi).',
                'Xem trước bảng dữ liệu trong giao diện tương tác.',
                'Nhấn "Tải Bảng Tính (.xlsx)" để lưu file Excel về máy tính.',
            ],
            'faq' => [
                [
                    'q' => 'Công cụ có nhận diện được bảng biểu trong file PDF không?',
                    'a' => 'Có. Thuật toán tự động phân tích các dòng văn bản có cấu trúc cột, số liệu và tiêu đề trong PDF để chuyển thành các ô tính toán tương ứng trong Excel.',
                ],
                [
                    'q' => 'Có giới hạn dung lượng hoặc số dòng khi chuyển đổi không?',
                    'a' => 'Công cụ xử lý trực tiếp trên RAM máy tính của bạn nên có thể chuyển đổi các tệp dữ liệu lên đến hàng chục nghìn dòng mà không gặp giới hạn của máy chủ.',
                ],
            ],
        ],

        // 15. Convert to Word (DOCX)
        'chuyen-sang-word' => [
            'slug' => 'chuyen-sang-word',
            'title' => 'Chuyển Đổi Tài Liệu Sang Word',
            'category' => 'finance',
            'badge' => 'Đa định dạng',
            'short_desc' => 'Chuyển đổi Markdown, HTML, Text, RTF và tệp tài liệu sang Microsoft Word (.docx) chuẩn phong cách.',
            'icon' => 'file-edit',
            'seo_title' => 'Chuyển Đổi Markdown, HTML, Text Sang Word (.DOCX) Trực Tuyến',
            'seo_desc' => 'Công cụ chuyển đổi các định dạng văn bản Markdown, mã nguồn HTML và văn bản thuần túy sang tài liệu Word DOCX với đầy đủ định dạng tiêu đề, danh sách, in đậm/nghiêng và bảng biểu.',
            'keywords' => 'chuyển sang word, markdown sang word, html to docx, text sang word, convert document to docx online',
            'how_to' => [
                'Tải file hoặc dán trực tiếp nội dung văn bản (Markdown, HTML, Text, RTF).',
                'Tùy chỉnh định dạng font chữ, lề tài liệu và kiểu tiêu đề mong muốn.',
                'Kiểm tra định dạng trong khung xem trước thời gian thực.',
                'Nhấn "Tải Xuống Tệp Word (.docx)" để xuất tài liệu.',
            ],
            'faq' => [
                [
                    'q' => 'Công cụ có giữ nguyên các định dạng như tiêu đề H1-H3, danh sách và bảng không?',
                    'a' => 'Có. Bộ phân tích cú pháp hỗ trợ chuyển đổi đầy đủ các cấu trúc Heading, Bullet List, Numbered List, In đậm, In nghiêng, Trích dẫn và Bảng biểu sang style tương ứng của Word.',
                ],
            ],
        ],

        // 16. AI Background Remover
        'xoa-phong-anh' => [
            'slug' => 'xoa-phong-anh',
            'title' => 'Xóa Phông Nền Ảnh Bằng AI',
            'category' => 'image',
            'badge' => 'AI Mới',
            'short_desc' => 'Tự động tách nền ảnh bằng AI, tạo phông trong suốt (.PNG), đổi phông trắng/xanh ảnh thẻ hoặc làm mờ phông bokeh chân dung tức thì.',
            'icon' => 'scissors',
            'seo_title' => 'Xóa Phông Nền Ảnh Bằng AI Online Miễn Phí - Tách Nền Trong Suốt Tức Thì',
            'seo_desc' => 'Công cụ xóa phông nền ảnh bằng trí tuệ nhân tạo (AI Background Remover) 100% miễn phí. Tách nền ảnh chân dung, ảnh thẻ, ảnh sản phẩm bán hàng không mất chi tiết tóc.',
            'keywords' => 'xóa phông ảnh ai, tách nền ảnh online, remove background ai free, đổi nền ảnh thẻ, làm mờ phông ảnh',
            'how_to' => [
                'Tải ảnh chân dung hoặc sản phẩm bạn muốn tách nền.',
                'Mô hình AI tự động quét chủ thể, bóc tách phông nền trong 0.5 giây.',
                'Chọn hiệu ứng: Nền trong suốt PNG, Phông màu (trắng, xanh, xám studio), Làm mờ phông bokeh, hoặc Ghép ảnh nền mới.',
                'Bấm "Tải Ảnh Về Máy" để lưu ảnh chất lượng cao độ phân giải gốc.',
            ],
            'faq' => [
                [
                    'q' => 'Mô hình AI nhận diện được những chi tiết khó như tóc hay viền áo không?',
                    'a' => 'Có! Mô hình Deep Learning phân đoạn ngữ nghĩa tự động phát hiện đường viền phức tạp của tóc tơ, nếp gấp quần áo và vật thể với độ chính xác cao.',
                ],
                [
                    'q' => 'Ảnh có bị gửi lên máy chủ của bên thứ ba không?',
                    'a' => 'Không. Mô hình AI WebAssembly chạy trực tiếp trong bộ nhớ trình duyệt của bạn (Client-Side AI Engine), hoàn toàn bảo mật và miễn phí 0đ.',
                ],
            ],
        ],

        // 17. AI Image Enhancer & Upscaler
        'nang-chat-luong-anh' => [
            'slug' => 'nang-chat-luong-anh',
            'title' => 'Nâng Cao Chất Lượng & Làm Nét Ảnh AI',
            'category' => 'image',
            'badge' => 'AI Hot',
            'short_desc' => 'Tăng độ phân giải 2x, 4x bằng AI Super-Resolution, phục hồi ảnh mờ, làm nét khuôn mặt, khử nhiễu với thanh so sánh Before/After trực quan.',
            'icon' => 'sparkles',
            'seo_title' => 'Nâng Cấp Chất Lượng & Làm Nét Ảnh Bằng AI Online Miễn Phí (Super-Resolution 4X)',
            'seo_desc' => 'Công cụ nâng cấp chất lượng ảnh bằng AI online tốt nhất. Phóng to ảnh 2x, 4x không vỡ hạt, làm nét ảnh cũ bị mờ, khử nhiễu ISO, phục hồi chi tiết khuôn mặt sắc nét.',
            'keywords' => 'làm nét ảnh ai, nâng chất lượng ảnh, ai image upscaler, phục hồi ảnh mờ online, tăng độ phân giải ảnh 4k',
            'how_to' => [
                'Chọn ảnh bị mờ, ảnh vỡ nét hoặc ảnh chụp kích thước nhỏ cần nâng cấp.',
                'Chọn tỷ lệ phóng to (2X, 4X) và chế độ tối ưu (Chân dung, Phong cảnh, Văn bản).',
                'Kéo thanh trượt Before/After trực quan để so sánh độ nét chi tiết trước và sau khi xử lý.',
                'Nhấn "Tải Ảnh Sắc Nét" để lưu về thiết bị.',
            ],
            'faq' => [
                [
                    'q' => 'Làm nét ảnh bằng AI khác gì so với phóng to ảnh thông thường?',
                    'a' => 'Phóng to thông thường khiến ảnh bị vỡ pixel và nhòe hạt. Thuật toán AI Super-Resolution sử dụng mạng nơ-ron để tái tạo các cạnh chi tiết, khử nhiễu hạt và khôi phục độ tương phản viền sắc sảo.',
                ],
            ],
        ],

        // 18. Canva Presentation Maker & Slide Editor
        'bai-thuyet-trinh' => [
            'slug' => 'bai-thuyet-trinh',
            'title' => 'Bài Thuyết Trình',
            'category' => 'graphics',
            'badge' => 'Canva Presentation',
            'short_desc' => 'Tạo bài thuyết trình online chuẩn phong cách Canva: Hàng chục mẫu slide đẹp 1920x1080, áp dụng 10 trang 1 chạm, thanh filmstrip slide đáy màn hình, nạp file PPTX cũ lên sửa trực tiếp, trình chiếu toàn màn hình và xuất file .pptx thật.',
            'icon' => 'presentation',
            'seo_title' => 'Tạo Bài Thuyết Trình Online Đẹp Như Canva & Chỉnh Sửa PowerPoint',
            'seo_desc' => 'Ứng dụng tạo bài thuyết trình trực tuyến phong cách Canva. Kho mẫu slide đa dạng tỉ lệ 1920x1080, áp dụng 10 trang 1 chạm, bóc tách và sửa file PowerPoint PPTX cũ, trình chiếu toàn màn hình và xuất file .pptx thật 100%.',
            'keywords' => 'bài thuyết trình canva, tạo slide thuyết trình online, sửa file pptx online, mẫu slide đẹp, làm bài thuyết trình, ziitool slide',
            'how_to' => [
                'Chọn một bộ mẫu bài thuyết trình (mẫu vui nhộn 10 trang, báo cáo doanh nghiệp, giáo dục, startup) hoặc nạp file .pptx cũ từ máy tính lên.',
                'Sử dụng thanh filmstrip ở đáy màn hình để duyệt qua các trang slide, thêm trang mới (+) hoặc sắp xếp thứ tự.',
                'Nhấp đúp chuột vào văn bản trên khung vẽ 16:9 để chỉnh sửa nội dung, thay đổi phông chữ, màu sắc, chèn hình khối và ảnh.',
                'Nhấn nút "Thuyết trình" (hoặc phím F5) để chiếu bài thuyết trình toàn màn hình.',
                'Nhấn "Chia sẻ" để tải file PowerPoint (.pptx) chuẩn hoặc xuất file PDF bài thuyết trình.',
            ],
            'faq' => [
                [
                    'q' => 'Tôi có thể tải file PowerPoint (.pptx) cũ lên để sửa trên giao diện Canva không?',
                    'a' => 'Có! Công cụ tích hợp bộ bóc tách file PPTX tự động, nạp toàn bộ các slide vào thanh filmstrip đáy màn hình để bạn chỉnh sửa trực tiếp.',
                ],
                [
                    'q' => 'File tải về có mở được trên Microsoft PowerPoint và Google Slides không?',
                    'a' => 'Được 100%! File .pptx được tạo chuẩn định dạng Office OpenXML, mở và chỉnh sửa được đầy đủ trên Microsoft PowerPoint, Google Slides, Keynote và LibreOffice.',
                ],
            ],
        ],
    ],
];
